<?php

declare(strict_types=1);

namespace HttpClientBinder\Fabrics;

use HttpClientBinder\Codec\DecoderInterface;
use HttpClientBinder\Codec\EncoderInterface;
use HttpClientBinder\Fabrics\Protocol\MagicProtocolFactory;
use HttpClientBinder\Mapping\Extractor\HeadersExtractor;
use HttpClientBinder\Mapping\Extractor\RequestTypeExtractor;
use HttpClientBinder\Mapping\Extractor\UrlParametersExtractor;
use HttpClientBinder\Mapping\MapFromAnnotation;
use HttpClientBinder\Protocol\MagicProtocolFactoryInterface;
use HttpClientBinder\Protocol\RemoteCall\RequestBuilder\BodyEncoder;
use HttpClientBinder\Protocol\RemoteCall\RequestBuilder\StreamBuilder;
use HttpClientBinder\Protocol\RemoteCall\RequestInterceptorChain;
use HttpClientBinder\Protocol\RemoteCall\RequestInterceptorInterface;
use HttpClientBinder\Protocol\RemoteCall\ResponseDecoder\ResponseDecoder;
use HttpClientBinder\Proxy\ProxyClassNameResolverInterface;
use HttpClientBinder\Proxy\ProxyFactory;
use HttpClientBinder\Proxy\ProxyFactoryRenderDecorator;
use HttpClientBinder\Proxy\ProxySourceRender;
use HttpClientBinder\Proxy\ProxySourceStorage;
use HttpClientBinder\Proxy\RenderDataFactory;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;

/**
 * @template T
 */
final class BinderBuilder
{
    private ProxyClassNameResolverInterface $classNameResolver;

    private EncoderInterface $encoder;

    private DecoderInterface $decoder;

    private RequestInterceptorInterface $requestInterceptor;

    private SerializerInterface $serializer;

    private string $tmpDir;

    /**
     * @param class-string<T> $className
     */
    public static function builder(string $className, ?string $baseUrl = null): BinderBuilder
    {
        return new self($className, $baseUrl);
    }

    public function encoder(EncoderInterface $encoder): BinderBuilder
    {
        $this->encoder = $encoder;

        return $this;
    }

    public function decoder(DecoderInterface $decoder): BinderBuilder
    {
        $this->decoder = $decoder;

        return $this;
    }

    public function requestInterceptor(RequestInterceptorInterface $interceptor): BinderBuilder
    {
        $this->requestInterceptor = $interceptor;

        return $this;
    }

    /**
     * @param RequestInterceptorInterface[] $interceptors
     */
    public function requestInterceptors(array $interceptors): BinderBuilder
    {
        $this->requestInterceptor = RequestInterceptorChain::create($interceptors);

        return $this;
    }

    public function temporaryDirectory(string $temporaryDirectory): BinderBuilder
    {
        $this->tmpDir = $temporaryDirectory;

        return $this;
    }

    /**
     * Returns instance of $className in method target
     * @return T
     */
    public function getClient(): mixed
    {
        $proxyFactory =
            new ProxyFactoryRenderDecorator(
                $this->classNameResolver,
                new ProxySourceRender(),
                new ProxySourceStorage($this->tmpDir),
                new RenderDataFactory(
                    $this->classNameResolver,
                    new MapFromAnnotation(
                        new UrlParametersExtractor(),
                        new HeadersExtractor(),
                        new RequestTypeExtractor()
                    ),
                    $this->serializer
                ),
                new ProxyFactory(
                    $this->classNameResolver,
                    $this->getMagicProtocolFactory()
                )
            );

        return $proxyFactory->build($this->className);
    }

    private function __construct(
        private readonly string $className,
        /* @param class-string<T> $className */
        private readonly ?string $baseUrl = null
    ) {
        $this->serializer = SerializerBuilder::create()->build();
        $this->encoder = $this->createDefaultEncoder();
        $this->decoder = $this->createDefaultDecoder();
        $this->classNameResolver = $this->createClassNameResolver();
        $this->requestInterceptor = RequestInterceptorChain::create();
        $this->tmpDir = "/tmp";
    }

    private function createClassNameResolver(): ProxyClassNameResolverInterface
    {
        return
            new class implements ProxyClassNameResolverInterface {
                public function resolve(string $interfaceName): string
                {
                    return
                        sprintf(
                            "%sProxy",
                            strtr(
                                $interfaceName,
                                [
                                    "Interface" => "",
                                    "\\" => "_",
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
                $this->decoder,
                $this->requestInterceptor,
                $this->baseUrl
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