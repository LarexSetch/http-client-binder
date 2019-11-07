<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Factory;

use Doctrine\Common\Annotations\Reader;
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
use HttpClientBinder\Mapping\Dto\Url;
use HttpClientBinder\Mapping\Dto\UrlParameter;
use HttpClientBinder\Mapping\Dto\UrlParameterBag;
use HttpClientBinder\Mapping\Enum\HttpMethod;
use HttpClientBinder\Mapping\Enum\UrlParameterType;
use HttpClientBinder\Mapping\Factory\Provider\ClientProvider;
use HttpClientBinder\Mapping\Factory\Provider\Dto\Method;
use HttpClientBinder\Mapping\Factory\Provider\MethodsProviderInterface;
use ReflectionClass;

final class MapFromAnnotation implements MappingFactoryInterface
{
    /**
     * @var ReflectionClass
     */
    private $reflectionClass;

    /**
     * @var ClientProvider
     */
    private $clientProvider;

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
        ClientProvider $clientProvider,
        MethodsProviderInterface $methodsProvider,
        Reader $annotationReader
    ) {
        $this->reflectionClass = $reflectionClass;
        $this->clientProvider = $clientProvider;
        $this->methodsProvider = $methodsProvider;
        $this->annotationReader = $annotationReader;
    }

    public function build(): Client
    {
        $endpoints = array_map([$this, 'createEndpoint'], $this->methodsProvider->provide());

        $client = $this->clientProvider->provide();

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
                $method->getResponseType(),
                $this->getRequestType($method)
            );
    }

    private function getHttpMethod(Method $method): HttpMethod
    {
        $requestMapping = $this->getRequestMapping($method);

        return HttpMethod::fromValue($requestMapping->getMethod());
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

    private function getRequestType(Method $method): string
    {
        /** @var RequestBody $requestBody */
        $requestBody =
            $this->annotationReader->getMethodAnnotation(
                $this->reflectionClass->getMethod($method->getName()),
                RequestBody::class
            );

        foreach($method->getArguments() as $argument) {
            if($argument->getName() === $requestBody->getArgumentName()) {
                return $argument->getType();
            }
        }

        //TODO throw undefined argument
    }

    private function getRequestMapping(Method $method): RequestMapping
    {
        /** @var RequestMapping $requestMapping */
        $requestMapping =
            $this->annotationReader->getMethodAnnotation(
                $this->reflectionClass->getMethod($method),
                RequestMapping::class
            );

        return $requestMapping;
    }
}