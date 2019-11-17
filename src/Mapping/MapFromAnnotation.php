<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping;

use Doctrine\Common\Annotations\Reader;
use DomainException;
use HttpClientBinder\Annotation\Header;
use HttpClientBinder\Annotation\HeaderBag;
use HttpClientBinder\Annotation\Parameter;
use HttpClientBinder\Annotation\ParameterBag;
use HttpClientBinder\Annotation\RequestBody;
use HttpClientBinder\Annotation\RequestMapping;
use HttpClientBinder\Mapping\Dto\Client;
use HttpClientBinder\Mapping\Dto\Endpoint;
use HttpClientBinder\Mapping\Dto\EndpointBag;
use HttpClientBinder\Mapping\Dto\HttpHeader;
use HttpClientBinder\Mapping\Dto\HttpHeaderBag;
use HttpClientBinder\Mapping\Dto\RequestType;
use HttpClientBinder\Mapping\Dto\Url;
use HttpClientBinder\Mapping\Dto\UrlParameter;
use HttpClientBinder\Mapping\Dto\UrlParameterBag;
use HttpClientBinder\Mapping\Enum\HttpMethod;
use HttpClientBinder\Mapping\Enum\UrlParameterType;
use HttpClientBinder\Method\Dto\Method;
use HttpClientBinder\Method\MethodsProviderInterface;
use Psr\Http\Message\StreamInterface;
use ReflectionClass;

final class MapFromAnnotation implements MappingBuilderInterface
{
    /**
     * @var ReflectionClass
     */
    private $reflectionClass;

    /**
     * @var MethodsProviderInterface
     */
    private $methodsProvider;

    /**
     * @var Reader
     */
    private $annotationReader;

    public function __construct(
        ReflectionClass $reflectionClass,
        MethodsProviderInterface $methodsProvider,
        Reader $annotationReader
    ) {
        $this->reflectionClass = $reflectionClass;
        $this->methodsProvider = $methodsProvider;
        $this->annotationReader = $annotationReader;
    }

    public function build(): Client
    {
        $endpoints = array_map([$this, 'createEndpoint'], $this->methodsProvider->provide());
        $client = $this->getClientAnnotation();

        return new Client($client->getBaseUrl(), new EndpointBag($endpoints));
    }

    private function createEndpoint(Method $method): Endpoint
    {
        return
            new Endpoint(
                $method->getName(),
                $this->getHttpMethod($method),
                $this->getUrl($method),
                $this->getHeaderBag($method),
                $this->getRequestType($method),
                $this->getResponseType($method)
            );
    }

    private function getHttpMethod(Method $method): string
    {
        return $this->getRequestMapping($method)->getMethod();
    }

    private function getUrl(Method $method): Url
    {
        return
            new Url(
                $this->getRequestMapping($method)->getUri(),
                $this->getUrlParameterBag($method)
            );
    }

    private function getUrlParameterBag(Method $method): UrlParameterBag
    {
        $parameterBag =
            $this->annotationReader->getMethodAnnotation(
                $this->reflectionClass->getMethod($method->getName()),
                ParameterBag::class
            );
        if (null === $parameterBag) {
            return new UrlParameterBag([]);
        }

        return
            new UrlParameterBag(array_map(
                function (Parameter $parameter) {
                    return
                        new UrlParameter(
                            $parameter->getArgumentName(),
                            $parameter->getAlias(),
                            UrlParameterType::fromValue($parameter->getType())
                        );
                },
                $parameterBag->getParameters()
            ));
    }

    private function getHeaderBag(Method $method): HttpHeaderBag
    {
        /** @var HeaderBag $headerBag */
        $headerBag =
            $this->annotationReader->getMethodAnnotation(
                $this->reflectionClass->getMethod($method->getName()),
                HeaderBag::class
            );
        if (null === $headerBag) {
            return new HttpHeaderBag([]);
        }

        return
            new HttpHeaderBag(
                array_map(
                    function (Header $header) {
                        return new HttpHeader($header->getHeader(), $header->getValues());
                    },
                    $headerBag->getHeaders()
                )
            );
    }

    private function getRequestType(Method $method): ?RequestType
    {
        /** @var RequestBody $requestBody */
        $requestBody =
            $this->annotationReader->getMethodAnnotation(
                $this->reflectionClass->getMethod($method->getName()),
                RequestBody::class
            );

        foreach ($method->getArguments() as $argument) {
            if ($argument->getName() === $requestBody->getArgumentName()) {
                return
                    new RequestType(
                        $argument->getName(),
                        $argument->getType()
                    );
            }
        }

        return null;
    }

    private function getRequestMapping(Method $method): RequestMapping
    {
        /** @var RequestMapping $requestMapping */
        $requestMapping =
            $this->annotationReader->getMethodAnnotation(
                $this->reflectionClass->getMethod($method->getName()),
                RequestMapping::class
            );

        return $requestMapping;
    }

    private function getClientAnnotation(): \HttpClientBinder\Annotation\Client
    {
        /** @var \HttpClientBinder\Annotation\Client $clientAnnotation */
        $clientAnnotation =
            $this->annotationReader->getClassAnnotation(
                $this->reflectionClass,
                \HttpClientBinder\Annotation\Client::class
            );

        if (null === $clientAnnotation) {
            throw new DomainException("You must define the Client annotation");
        }

        return $clientAnnotation;
    }

    private function getResponseType(Method $method): string
    {
        return
            null === $method->getReturnType()
                ? StreamInterface::class
                : $method->getReturnType();
    }
}