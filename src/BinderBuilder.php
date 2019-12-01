<?php

declare(strict_types=1);

namespace HttpClientBinder;

use HttpClientBinder\Codec\DecoderInterface;
use HttpClientBinder\Codec\EncoderInterface;
use HttpClientBinder\Mapping\MapFromAnnotationFactory;
use HttpClientBinder\Method\ReflectionMethodsProviderFactory;
use HttpClientBinder\Proxy\ProxyFactory;
use HttpClientBinder\Proxy\ProxySourceRender;
use HttpClientBinder\Proxy\ProxySourceStorage;
use JMS\Serializer\SerializerBuilder;

final class BinderBuilder implements BinderBuilderInterface
{
    private $proxyFactory;

    private $className;

    public static function builder(): BinderBuilderInterface
    {
        return new BinderBuilder();
    }

    public function target(string $className, string $url): BinderBuilderInterface
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
        $this->proxyFactory = new ProxyFactory(
            new MapFromAnnotationFactory(),
            SerializerBuilder::create()->build(),
            new ProxySourceRender(),
            new ProxySourceStorage(TMP_DIR),
            new ReflectionMethodsProviderFactory()
        );

        return $this->proxyFactory->build($this->className);
    }

    private function __construct() {}
}