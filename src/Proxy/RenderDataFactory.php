<?php

declare(strict_types=1);

namespace HttpClientBinder\Proxy;

use HttpClientBinder\Mapping\MappingBuilderFactoryInterface;
use HttpClientBinder\Method\MethodsProviderFactoryInterface;
use HttpClientBinder\Protocol\MagicProtocol;
use HttpClientBinder\Proxy\Dto\RenderData;
use JMS\Serializer\SerializerInterface;

final class RenderDataFactory implements RenderDataFactoryInterface
{
    /**
     * @var ProxyClassNameResolverInterface
     */
    private $classNameResolver;

    /**
     * @var MappingBuilderFactoryInterface
     */
    private $mappingBuilderFactory;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var MethodsProviderFactoryInterface
     */
    private $methodsProviderFactory;

    public function __construct(
        ProxyClassNameResolverInterface $classNameResolver,
        MappingBuilderFactoryInterface $mappingBuilderFactory,
        SerializerInterface $serializer,
        MethodsProviderFactoryInterface $methodsProviderFactory
    ) {
        $this->classNameResolver = $classNameResolver;
        $this->mappingBuilderFactory = $mappingBuilderFactory;
        $this->serializer = $serializer;
        $this->methodsProviderFactory = $methodsProviderFactory;
    }

    public function build(string $interfaceName): RenderData
    {
        return
            new RenderData(
                $this->classNameResolver->resolve($interfaceName),
                $interfaceName,
                MagicProtocol::class,
                $this->getJsonString($interfaceName),
                $this->methodsProviderFactory->build($interfaceName)->provide()
            );
    }

    private function getJsonString(string $interfaceName): string
    {
        return
            strtr(
                $this->serializer->serialize(
                    $this->mappingBuilderFactory->create($interfaceName)->build(),
                    'json'
                ),
                [
                    '\\\\' => '\\\\\\'
                ]
            );
    }
}