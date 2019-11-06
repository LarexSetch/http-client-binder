<?php

declare(strict_types=1);

namespace HttpClientBinder\Provider;

use Doctrine\Common\Annotations\Reader;
use HttpClientBinder\Annotation\HeaderBag;
use HttpClientBinder\Annotation\ParameterBag;
use HttpClientBinder\Annotation\RequestBody;
use HttpClientBinder\Annotation\RequestMapping;
use HttpClientBinder\Mapping\Dto\ResponseBody;
use HttpClientBinder\Provider\Dto\Argument;
use HttpClientBinder\Provider\Dto\Method;
use HttpClientBinder\Provider\Exception\AnnotationNotDefinedProviderException;
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

    /**
     * @throws AnnotationNotDefinedProviderException
     */
    private function createMethod(ReflectionMethod $reflectionMethod): Method
    {
        /** @var RequestBody $requestBody */
        $requestBody = $this->annotationReader->getMethodAnnotation($reflectionMethod, RequestBody::class);

        return
            new Method(
                $reflectionMethod->getName(),
                $this->getRequestMapping($reflectionMethod),
                $this->getHeaderBag($reflectionMethod),
                $this->getParameterBag($reflectionMethod),
                $requestBody,
                $this->getResponseType($reflectionMethod),
                $this->getRequestType($reflectionMethod, $requestBody),
                $this->getArguments($reflectionMethod)
            );
    }

    /**
     * @throws AnnotationNotDefinedProviderException
     */
    private function getRequestMapping(ReflectionMethod $reflectionMethod): RequestMapping
    {
        /** @var RequestMapping $requestMapping */
        $requestMapping = $this->annotationReader->getMethodAnnotation($reflectionMethod, RequestMapping::class);

        if(null === $requestMapping) {
            throw new AnnotationNotDefinedProviderException('The annotation @RequestMapping is required');
        }

        return $requestMapping;
    }

    private function getHeaderBag(ReflectionMethod $reflectionMethod): HeaderBag
    {
        $headerBag = $this->annotationReader->getMethodAnnotation($reflectionMethod, HeaderBag::class);

        return $headerBag ?? new HeaderBag([]);
    }

    private function getParameterBag(ReflectionMethod $reflectionMethod): ParameterBag
    {
        $parameterBag = $this->annotationReader->getMethodAnnotation($reflectionMethod, ParameterBag::class);

        return $parameterBag ?? new ParameterBag([]);
    }

    private function getRequestType(ReflectionMethod $reflectionMethod, ?RequestBody $requestBody): ?string
    {
        if (null === $requestBody) {
            return null;
        }

        foreach ($reflectionMethod->getParameters() as $parameter) {
            if ($requestBody->getArgumentName() === $parameter->getName()) {
                return
                    null === $parameter->getType()
                        ? null
                        : $parameter->getType()->getName();
            }
        }

        return null;
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