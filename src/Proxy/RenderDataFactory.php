<?php

declare(strict_types=1);

namespace HttpClientBinder\Proxy;

use DomainException;
use HttpClientBinder\Mapping\MappingBuilderFactoryInterface;
use HttpClientBinder\Protocol\MagicProtocol;
use HttpClientBinder\Proxy\Dto\Method;
use HttpClientBinder\Proxy\Dto\MethodArgument;
use HttpClientBinder\Proxy\Dto\RenderData;
use JMS\Serializer\SerializerInterface;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;

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

    public function __construct(
        ProxyClassNameResolverInterface $classNameResolver,
        MappingBuilderFactoryInterface $mappingBuilderFactory,
        SerializerInterface $serializer
    ) {
        $this->classNameResolver = $classNameResolver;
        $this->mappingBuilderFactory = $mappingBuilderFactory;
        $this->serializer = $serializer;
    }

    public function build(string $interfaceName): RenderData
    {
        return
            new RenderData(
                $this->classNameResolver->resolve($interfaceName),
                $interfaceName,
                MagicProtocol::class,
                $this->getJsonString($interfaceName),
                $this->buildMethods($interfaceName)
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

    private function buildMethods(string $interfaceName): array
    {
        $reflectionClass = new ReflectionClass($interfaceName);

        return
            array_map(
                function (ReflectionMethod $method) {
                    return
                        new Method(
                            $method->getName(),
                            $this->getReturnType($method),
                            array_map(
                                function (ReflectionParameter $parameter) {
                                    return
                                        new MethodArgument(
                                            $parameter->getName(),
                                            $this->getArgumentType($parameter)
                                        );
                                },
                                $method->getParameters()
                            )
                        );
                },
                $reflectionClass->getMethods()
            );
    }

    private function getReturnType(ReflectionMethod $method): string
    {
        $type = $method->getReturnType();
        if(null === $type) {
            throw new DomainException(sprintf('You mus define return type for method %s', $method->getName()));
        }

        return $type->getName();
    }

    private function getArgumentType(ReflectionParameter $parameter): string
    {
        $type = $parameter->getType();
        if(null === $type) {
            throw new DomainException(sprintf('You mus define return type for parameter %s', $parameter->getName()));
        }

        return $type->getName();
    }
}