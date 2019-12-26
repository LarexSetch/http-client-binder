<?php

namespace HttpClientBinder\Mapping\Extractor;

use Doctrine\Common\Annotations\Reader;
use HttpClientBinder\Annotation\Header;
use HttpClientBinder\Annotation\HeaderBag;
use HttpClientBinder\Annotation\Parameter;
use HttpClientBinder\Annotation\ParameterBag;
use HttpClientBinder\Annotation\RequestMapping;
use HttpClientBinder\Mapping\Dto\HttpHeader;
use HttpClientBinder\Mapping\Dto\HttpHeaderBag;
use HttpClientBinder\Mapping\Dto\HttpHeaderParameter;
use ReflectionMethod;
use DomainException;

final class HeadersExtractor implements HeadersExtractorInterface
{
    /**
     * @var Reader
     */
    private $annotationReader;

    public function __construct(Reader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    public function extract(ReflectionMethod $method): HttpHeaderBag
    {
        /** @var HeaderBag $headerBag */
        $headers = $this->initHeaders($method);
        $headerBag = $this->annotationReader->getMethodAnnotation($method, HeaderBag::class);
        if (null === $headerBag) {
            return new HttpHeaderBag($headers);
        }

        $headers =
            array_merge(
                $headers,
                array_map(
                    function (Header $header) use ($method) {
                        return $this->createHttpHeader($header, $method);
                    },
                    $headerBag->getHeaders()
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
        if (null !== $requestMapping->getRequestType()) {
            return [new HttpHeader('Content-type', [$requestMapping->getRequestType()])];
        } else {
            return [];
        }
    }

    private function getRequestMapping(ReflectionMethod $method): RequestMapping
    {
        /** @var RequestMapping $requestMapping */
        $requestMapping = $this->annotationReader->getMethodAnnotation($method, RequestMapping::class);

        return $requestMapping;
    }

    private function createHttpHeader(Header $header, ReflectionMethod $method): HttpHeader
    {
        /** @var ParameterBag $parameterBag */
        $parameterBag = $this->annotationReader->getMethodAnnotation($method, ParameterBag::class);
        $headerParameters = array_map(
            function (Parameter $parameter) use ($method) {
                return $this->createHeaderParameter($parameter, $method);
            },
            array_filter(
                $parameterBag->getParameters(),
                function (Parameter $parameter) {
                    return in_array(Parameter::TYPE_HEADER, $parameter->getTypes());
                }
            )
        );

        return new HttpHeader($header->getHeader(), $header->getValues(), $headerParameters);
    }

    private function createHeaderParameter(Parameter $parameter, ReflectionMethod $method): HttpHeaderParameter
    {
        return
            new HttpHeaderParameter(
                $parameter->getArgumentName(),
                $this->getArgumentIndex($parameter, $method),
                $parameter->getAlias()
            );
    }

    private function getArgumentIndex(Parameter $parameter, ReflectionMethod $method): int
    {
        foreach ($method->getParameters() as $methodParameter) {
            if ($methodParameter->getName() === $parameter->getArgumentName()) {
                return $methodParameter->getPosition();
            }
        }

        throw new DomainException(sprintf(
            'Unexpected parameter %s in method %s with type %s',
            $parameter->getArgumentName(),
            $method->getName(),
            Parameter::TYPE_HEADER
        ));
    }
}