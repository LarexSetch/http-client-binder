<?php

declare(strict_types=1);

namespace HttpClientBinder\Proxy;

use HttpClientBinder\Mapping\Factory\MappingFactoryInterface;
use HttpClientBinder\Mapping\Factory\Provider\MethodsProviderInterface;
use HttpClientBinder\Method\MethodsProviderFactoryInterface;
use HttpClientBinder\Protocol\MagicProtocol;
use HttpClientBinder\Proxy\Dto\RenderData;
use JMS\Serializer\SerializerInterface;

final class ProxyFactory implements ProxyFactoryInterface
{
    /**
     * @var MappingFactoryInterface
     */
    private $mappingFactory;

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

    private function __construct(
        MappingFactoryInterface $mappingFactory,
        SerializerInterface $serializer,
        SourceRenderInterface $sourceRender,
        SourceStorageInterface $sourceStorage,
        MethodsProviderFactoryInterface $methodsProviderFactory
    ) {
        $this->mappingFactory = $mappingFactory;
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
        $source = $this->sourceRender->render($this->createRenderData($interfaceName));
        $this->sourceStorage->store($interfaceName, $source);
        $this->sourceStorage->import($interfaceName);
        $className = $this->getProxyClassName($interfaceName);

        return new $className($this->serializer);
    }

    private function createRenderData(string $interfaceName): RenderData
    {
        return
            new RenderData(
                $this->getProxyClassName($interfaceName),
                $interfaceName,
                MagicProtocol::class,
                $this->serializer->serialize($this->mappingFactory->build(), 'json'),
                $this->methodsProviderFactory->build($interfaceName)->provide()
            );
    }

    private function getProxyClassName(string $interfaceName): string
    {
        return
            sprintf(
                "%sProxy",
                str_replace("Interface", "", $interfaceName)
            );
    }
}