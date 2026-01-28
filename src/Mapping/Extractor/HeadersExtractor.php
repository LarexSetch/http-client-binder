<?php

namespace HttpClientBinder\Mapping\Extractor;

use DomainException;
use HttpClientBinder\Annotation\Header;
use HttpClientBinder\Annotation\HeaderBag;
use HttpClientBinder\Annotation\Parameter;
use HttpClientBinder\Annotation\ParameterType;
use HttpClientBinder\Annotation\RequestMapping;
use HttpClientBinder\Mapping\Dto\HttpHeader;
use HttpClientBinder\Mapping\Dto\HttpHeaderBag;
use HttpClientBinder\Mapping\Dto\HttpHeaderParameter;
use ReflectionAttribute;
use ReflectionMethod;

final class HeadersExtractor implements HeadersExtractorInterface
{
    public function extract(ReflectionMethod $method): HttpHeaderBag
    {
        /** @var HeaderBag $headerBag */
        $headers = $this->initHeaders($method);
        $reflectionAttributes = $method->getAttributes(Header::class);
        if (empty($reflectionAttributes)) {
            return new HttpHeaderBag($headers);
        }

        $headers =
            array_merge(
                $headers,
                array_map(
                    function (Header $header) use ($method) {
                        return $this->createHttpHeader($header, $method);
                    },
                    array_map(fn(ReflectionAttribute $attribute) => $attribute->newInstance(), $reflectionAttributes)
                )
            );

        return new HttpHeaderBag($headers);
    }

    /**
     * @return HttpHeader[]
     */
    private function initHeaders(ReflectionMethod $method): array
    {
        $requestMapping = $this->getRequestMapping($method);
        if (null !== $requestMapping->requestType) {
            return [new HttpHeader('Content-type', [$requestMapping->requestType])];
        } else {
            return [];
        }
    }

    private function getRequestMapping(ReflectionMethod $method): ?RequestMapping
    {
        $reflectionAttribute = $method->getAttributes(RequestMapping::class)[0] ?? null;
        if (null === $reflectionAttribute) {
            return null;
        }

        /** @var RequestMapping $requestMapping */
        $requestMapping = $reflectionAttribute->newInstance();

        return $requestMapping;
    }

    private function createHttpHeader(Header $header, ReflectionMethod $method): HttpHeader
    {
        $reflectionAttributes = $method->getAttributes(Parameter::class);
        $headerParameters = array_map(
            function (Parameter $parameter) use ($method) {
                return $this->createHeaderParameter($parameter, $method);
            },
            array_values(
                array_filter(
                    array_map(fn(ReflectionAttribute $attribute) => $attribute->newInstance(), $reflectionAttributes),
                    function (Parameter $parameter) {
                        return $parameter->type === ParameterType::HEADER;
                    }
                )
            )
        );

        return new HttpHeader($header->header, $header->getValues(), $headerParameters);
    }

    private function createHeaderParameter(Parameter $parameter, ReflectionMethod $method): HttpHeaderParameter
    {
        return
            new HttpHeaderParameter(
                $parameter->argumentName,
                $this->getArgumentIndex($parameter, $method),
                $parameter->alias
            );
    }

    private function getArgumentIndex(Parameter $parameter, ReflectionMethod $method): int
    {
        foreach ($method->getParameters() as $methodParameter) {
            if ($methodParameter->getName() === $parameter->argumentName) {
                return $methodParameter->getPosition();
            }
        }

        throw new DomainException(
            sprintf(
                'Unexpected parameter %s in method %s with type %s',
                $parameter->argumentName,
                $method->getName(),
                ParameterType::HEADER->value
            )
        );
    }
}