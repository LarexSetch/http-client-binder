<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping;

use Doctrine\Common\Annotations\Reader;
use DomainException;
use HttpClientBinder\Annotation\Client;
use HttpClientBinder\Annotation\RequestMapping;
use HttpClientBinder\Mapping\Dto\MappingClient;
use HttpClientBinder\Mapping\Dto\Endpoint;
use HttpClientBinder\Mapping\Dto\EndpointBag;
use HttpClientBinder\Mapping\Dto\Url;
use HttpClientBinder\Mapping\Extractor\HeadersExtractorInterface;
use HttpClientBinder\Mapping\Extractor\RequestTypeExtractorInterface;
use HttpClientBinder\Mapping\Extractor\UrlParametersExtractorInterface;
use ReflectionClass;
use ReflectionMethod;

final class MapFromAnnotation implements MappingBuilderInterface
{
    /**
     * @var ReflectionClass
     */
    private $reflectionClass;

    /**
     * @var Reader
     */
    private $annotationReader;

    /**
     * @var UrlParametersExtractorInterface
     */
    private $urlParametersExtractor;

    /**
     * @var HeadersExtractorInterface
     */
    private $headersExtractor;

    /**
     * @var RequestTypeExtractorInterface
     */
    private $requestTypeExtractor;

    public function __construct(
        ReflectionClass $reflectionClass,
        Reader $annotationReader,
        UrlParametersExtractorInterface $urlParametersExtractor,
        HeadersExtractorInterface $headersExtractor,
        RequestTypeExtractorInterface $requestTypeExtractor
    ) {
        $this->reflectionClass = $reflectionClass;
        $this->annotationReader = $annotationReader;
        $this->urlParametersExtractor = $urlParametersExtractor;
        $this->headersExtractor = $headersExtractor;
        $this->requestTypeExtractor = $requestTypeExtractor;
    }

    public function build(): MappingClient
    {
        $client = $this->getClientAnnotation();
        $endpoints = array_map([$this, 'createEndpoint'], $this->reflectionClass->getMethods());

        return new MappingClient(new EndpointBag($endpoints), $client->getBaseUrl());
    }

    private function getClientAnnotation(): Client
    {
        /** @var Client $clientAnnotation */
        $clientAnnotation = $this->annotationReader->getClassAnnotation($this->reflectionClass, Client::class);
        if (null === $clientAnnotation) {
            throw new DomainException("You must define the Client annotation");
        }

        return $clientAnnotation;
    }

    private function createEndpoint(ReflectionMethod $method): Endpoint
    {
        return
            new Endpoint(
                $method->getName(),
                $this->getRequestMapping($method)->getMethod(),
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
                $this->getRequestMapping($method)->getUri(),
                $this->urlParametersExtractor->extract($method)
            );
    }

    private function getRequestMapping(ReflectionMethod $method): RequestMapping
    {
        /** @var RequestMapping $requestMapping */
        $requestMapping = $this->annotationReader->getMethodAnnotation($method, RequestMapping::class);

        return $requestMapping;
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