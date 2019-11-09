<?php

declare(strict_types=1);

namespace HttpClientBinder\Proxy;

use HttpClientBinder\Mapping\MappingBuilderFactoryInterface;
use HttpClientBinder\Method\MethodsProviderFactoryInterface;
use HttpClientBinder\Protocol\MagicProtocol;
use HttpClientBinder\Proxy\Dto\RenderData;
use JMS\Serializer\SerializerInterface;

final class ProxyFactory implements ProxyFactoryInterface
{
    /**
     * @var MappingBuilderFactoryInterface
     */
    private $mappingBuilderFactory;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var SourceRenderInterface
     */
    private $sourceRender;

    /**
     * @var SourceStorageInterface
     */
    private $sourceStorage;

    /**
     * @var MethodsProviderFactoryInterface
     */
    private $methodsProviderFactory;

    public function __construct(
        MappingBuilderFactoryInterface $mappingBuilderFactory,
        SerializerInterface $serializer,
        SourceRenderInterface $sourceRender,
        SourceStorageInterface $sourceStorage,
        MethodsProviderFactoryInterface $methodsProviderFactory
    ) {
        $this->mappingBuilderFactory = $mappingBuilderFactory;
        $this->serializer = $serializer;
        $this->sourceRender = $sourceRender;
        $this->sourceStorage = $sourceStorage;
        $this->methodsProviderFactory = $methodsProviderFactory;
    }

    /**
     * @return mixed implementation of some $className
     */
    public function build(string $interfaceName)
    {
        $className = $this->getProxyClassName($interfaceName);
        $source = $this->sourceRender->render($this->createRenderData($interfaceName));
        $this->sourceStorage->store($className, $source);
        $this->sourceStorage->import($className);

        return new $className($this->serializer);
    }

    private function createRenderData(string $interfaceName): RenderData
    {
        return
            new RenderData(
                $this->getProxyClassName($interfaceName),
                $interfaceName,
                MagicProtocol::class,
                $this->serializer->serialize($this->mappingBuilderFactory->create($interfaceName)->build(), 'json'),
                $this->methodsProviderFactory->build($interfaceName)->provide()
            );
    }

    private function getProxyClassName(string $interfaceName): string
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
}