<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping;

use DomainException;
use HttpClientBinder\Annotation\Client;
use HttpClientBinder\Annotation\Header;
use HttpClientBinder\Annotation\RequestMapping;
use HttpClientBinder\Mapping\Dto\Endpoint;
use HttpClientBinder\Mapping\Dto\EndpointBag;
use HttpClientBinder\Mapping\Dto\HttpHeader;
use HttpClientBinder\Mapping\Dto\HttpHeaderBag;
use HttpClientBinder\Mapping\Dto\MappingClient;
use HttpClientBinder\Mapping\Dto\Url;
use HttpClientBinder\Mapping\Extractor\HeadersExtractorInterface;
use HttpClientBinder\Mapping\Extractor\RequestTypeExtractorInterface;
use HttpClientBinder\Mapping\Extractor\UrlParametersExtractorInterface;
use ReflectionClass;
use ReflectionMethod;

readonly final class MapFromAnnotation implements MappingBuilderInterface
{
    public function __construct(
        private UrlParametersExtractorInterface $urlParametersExtractor,
        private HeadersExtractorInterface $headersExtractor,
        private RequestTypeExtractorInterface $requestTypeExtractor
    ) {
    }

    public function build(string $interfaceName): MappingClient
    {
        $reflectionClass = new ReflectionClass($interfaceName);
        $client = $this->getClientAnnotation($reflectionClass);
        $endpoints = array_map([$this, 'createEndpoint'], $reflectionClass->getMethods());

        return
            new MappingClient(
                new EndpointBag($endpoints),
                new HttpHeaderBag(
                    array_map(
                        function (Header $header) {
                            return new HttpHeader($header->header, $header->getValues());
                        },
                        $client->headers
                    )
                ),
                $client->baseUrl
            );
    }

    private function getClientAnnotation(ReflectionClass $reflectionClass): Client
    {
        $reflectionAttribute = $reflectionClass->getAttributes(Client::class)[0] ?? null;
        if (null === $reflectionAttribute) {
            throw new DomainException(
                sprintf('You must define the #[%s] in %s', Client::class, $reflectionClass->getName())
            );
        }

        /** @var Client $client */
        $client = $reflectionAttribute->newInstance();

        return $client;
    }

    private function createEndpoint(ReflectionMethod $method): Endpoint
    {
        return
            new Endpoint(
                $method->getName(),
                $this->getRequestMapping($method)->method,
                $this->getResponseType($method),
                $this->getUrl($method),
                $this->headersExtractor->extract($method),
                $this->requestTypeExtractor->extract($method)
            );
    }

    private function getUrl(ReflectionMethod $method): Url
    {
        return
            new Url(
                $this->getRequestMapping($method)->uri,
                $this->urlParametersExtractor->extract($method)
            );
    }

    private function getRequestMapping(ReflectionMethod $reflectionMethod): RequestMapping
    {
        $reflectionAttribute = $reflectionMethod->getAttributes(RequestMapping::class)[0] ?? null;
        if (null === $reflectionAttribute) {
            throw new DomainException(
                sprintf('You must define the #[%s] in %s', RequestMapping::class, $reflectionMethod->getNamespaceName())
            );
        }

        /** @var RequestMapping $mapping */
        $mapping = $reflectionAttribute->newInstance();

        return $mapping;
    }

    private function getResponseType(ReflectionMethod $method): string
    {
        $reflectionType = $method->getReturnType();
        if (null !== $reflectionType) {
            return $reflectionType->getName();
        }

        throw new DomainException(sprintf("You must define return type for %s method", $method->getName()));
    }
}