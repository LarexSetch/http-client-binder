<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Factory\Provider;

use Doctrine\Common\Annotations\Reader;
use HttpClientBinder\Mapping\Factory\Provider\Dto\Argument;
use HttpClientBinder\Mapping\Factory\Provider\Dto\Method;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;

final class ReflectionMethodsProviderInterface implements MethodsProviderInterface
{
    /**
     * @var ReflectionClass
     */
    private $reflectionClass;

    public function __construct(ReflectionClass $reflectionClass)
    {
        $this->reflectionClass = $reflectionClass;
    }

    public function provide(): array
    {
        return array_map([$this, 'createMethod'], $this->reflectionClass->getMethods());
    }

    private function createMethod(ReflectionMethod $reflectionMethod): Method
    {
        return
            new Method(
                $reflectionMethod->getName(),
                $this->getResponseType($reflectionMethod),
                $this->getArguments($reflectionMethod)
            );
    }

    private function getArguments(ReflectionMethod $reflectionMethod): array
    {
        return
            array_map(
                function (ReflectionParameter $reflectionParameter) {
                    $reflectionType = $reflectionParameter->getType();

                    return new Argument(
                        $reflectionParameter->getName(),
                        null !== $reflectionType
                            ? $reflectionType->getName()
                            : null
                    );
                },
                $reflectionMethod->getParameters()
            );
    }

    private function getResponseType(ReflectionMethod $reflectionMethod): ?string
    {
        $reflectionType = $reflectionMethod->getReturnType();

        return
            null !== $reflectionType
                ? $reflectionType->getName()
                : null;
    }
}