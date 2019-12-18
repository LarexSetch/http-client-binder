<?php

declare(strict_types=1);

namespace HttpClientBinder;

use HttpClientBinder\Codec\DecoderInterface;
use HttpClientBinder\Codec\EncoderInterface;
use HttpClientBinder\Mapping\MapFromAnnotationFactory;
use HttpClientBinder\Method\ReflectionMethodsProviderFactory;
use HttpClientBinder\Protocol\MagicProtocolFactory;
use HttpClientBinder\Protocol\MagicProtocolFactoryInterface;
use HttpClientBinder\Protocol\RequestBuilder\BodyEncoder;
use HttpClientBinder\Protocol\RequestBuilder\StreamBuilder;
use HttpClientBinder\Protocol\ResponseDecoder\ResponseDecoder;
use HttpClientBinder\Proxy\ProxyClassNameResolverInterface;
use HttpClientBinder\Proxy\ProxyFactory;
use HttpClientBinder\Proxy\ProxyFactoryRenderDecorator;
use HttpClientBinder\Proxy\ProxySourceRender;
use HttpClientBinder\Proxy\ProxySourceStorage;
use HttpClientBinder\Proxy\RenderDataFactory;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;

final class BinderBuilder implements BinderBuilderInterface
{
    /**
     * @var ProxyClassNameResolverInterface
     */
    private $classNameResolver;

    /**
     * @var EncoderInterface
     */
    private $encoder;

    /**
     * @var DecoderInterface
     */
    private $decoder;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var string
     */
    private $className;

    /**
     * @var string
     */
    private $tmpDir;

    public static function builder(): BinderBuilderInterface
    {
        return new BinderBuilder();
    }

    public function encoder(EncoderInterface $encoder): BinderBuilderInterface
    {
        $this->encoder = $encoder;

        return $this;
    }

    public function decoder(DecoderInterface $decoder): BinderBuilderInterface
    {
        $this->decoder = $decoder;

        return $this;
    }

    public function temporaryDirectory(string $temporaryDirectory): BinderBuilderInterface
    {
        $this->tmpDir = $temporaryDirectory;

        return $this;
    }

    public function target(string $className, string $url = null): BinderBuilderInterface
    {
        $this->className = $className;

        return $this;
    }

    /**
     * Returns instance of $className in method target
     * @return mixed
     */
    public function getClient()
    {
        $proxyFactory =
            new ProxyFactoryRenderDecorator(
                $this->classNameResolver,
                new ProxySourceRender(),
                new ProxySourceStorage($this->tmpDir),
                new RenderDataFactory(
                    $this->classNameResolver,
                    new MapFromAnnotationFactory(),
                    $this->serializer,
                    new ReflectionMethodsProviderFactory()
                ),
                new ProxyFactory(
                    $this->classNameResolver,
                    $this->getMagicProtocolFactory()
                )
            );

        return $proxyFactory->build($this->className);
    }

    private function __construct()
    {
        $this->serializer = SerializerBuilder::create()->build();
        $this->encoder = $this->createDefaultEncoder();
        $this->decoder = $this->createDefaultDecoder();
        $this->classNameResolver = $this->createClassNameResolver();
        $this->tmpDir = "/tmp";
    }

    private function createClassNameResolver(): ProxyClassNameResolverInterface
    {
        return
            new class implements ProxyClassNameResolverInterface
            {
                public function resolve(string $interfaceName): string
                {
                    return
                        sprintf(
                            "%sProxy",
                            strtr(
                                $interfaceName,
                                [
                                    "Interface" => "",
                                    "\\" => "_"
                                ]
                            )
                        );
                }
            };
    }

    private function getMagicProtocolFactory(): MagicProtocolFactoryInterface
    {
        return
            new MagicProtocolFactory(
                $this->serializer,
                $this->encoder,
                $this->decoder
            );
    }

    private function createDefaultEncoder(): EncoderInterface
    {
        return
            new BodyEncoder(
                $this->serializer,
                new StreamBuilder()
            );
    }

    private function createDefaultDecoder(): DecoderInterface
    {
        return new ResponseDecoder($this->serializer);
    }
}