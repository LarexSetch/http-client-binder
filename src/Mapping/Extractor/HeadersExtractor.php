<?php

namespace HttpClientBinder\Mapping\Extractor;

use Doctrine\Common\Annotations\Reader;
use HttpClientBinder\Annotation\Header;
use HttpClientBinder\Annotation\HeaderBag;
use HttpClientBinder\Annotation\RequestMapping;
use HttpClientBinder\Mapping\Dto\HttpHeader;
use HttpClientBinder\Mapping\Dto\HttpHeaderBag;
use ReflectionMethod;

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
                    function (Header $header) {
                        return new HttpHeader($header->getHeader(), $header->getValues());
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
}