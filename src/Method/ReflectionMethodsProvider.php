<?php

declare(strict_types=1);

namespace HttpClientBinder\Method;

use HttpClientBinder\Method\Dto\Argument;
use HttpClientBinder\Method\Dto\Method;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;

final class ReflectionMethodsProvider implements MethodsProviderInterface
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
                $this->getReturnType($reflectionMethod),
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

    private function getReturnType(ReflectionMethod $reflectionMethod): ?string
    {
        $reflectionType = $reflectionMethod->getReturnType();

        return
            null !== $reflectionType
                ? $reflectionType->getName()
                : null;
    }
}