<?php

declare(strict_types=1);

namespace HttpClientBinder\Provider;

use Doctrine\Common\Annotations\Reader;
use HttpClientBinder\Annotation\HeaderBag;
use HttpClientBinder\Annotation\ParameterBag;
use HttpClientBinder\Annotation\RequestBody;
use HttpClientBinder\Annotation\RequestMapping;
use HttpClientBinder\Provider\Dto\Argument;
use HttpClientBinder\Provider\Dto\Method;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;

final class ReflectionMethodsProvider implements MethodsProvider
{
    /**
     * @var ReflectionClass
     */
    private $reflectionClass;

    /**
     * @var Reader
     */
    private $annotationReader;

    public function __construct(ReflectionClass $reflectionClass, Reader $annotationReader)
    {
        $this->reflectionClass = $reflectionClass;
        $this->annotationReader = $annotationReader;
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
                $this->annotationReader->getMethodAnnotation($reflectionMethod, RequestMapping::class),
                $this->annotationReader->getMethodAnnotation($reflectionMethod, HeaderBag::class),
                $this->annotationReader->getMethodAnnotation($reflectionMethod, ParameterBag::class),
                $this->annotationReader->getMethodAnnotation($reflectionMethod, RequestBody::class),
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