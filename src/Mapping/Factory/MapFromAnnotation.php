<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Factory;

use Doctrine\Common\Annotations\Reader;
use HttpClientBinder\Annotation\Header;
use HttpClientBinder\Annotation\Parameter;
use HttpClientBinder\Mapping\Dto\Client;
use HttpClientBinder\Mapping\Dto\Endpoint;
use HttpClientBinder\Mapping\Dto\EndpointBag;
use HttpClientBinder\Mapping\Dto\HttpHeader;
use HttpClientBinder\Mapping\Dto\HttpHeaderBag;
use HttpClientBinder\Mapping\Dto\RequestBody;
use HttpClientBinder\Mapping\Dto\ResponseBody;
use HttpClientBinder\Mapping\Dto\Url;
use HttpClientBinder\Mapping\Dto\UrlParameter;
use HttpClientBinder\Mapping\Dto\UrlParameterBag;
use HttpClientBinder\Mapping\Enum\HttpMethod;
use HttpClientBinder\Mapping\Enum\SerializationType;
use HttpClientBinder\Provider\ClientProvider;
use HttpClientBinder\Provider\Dto\Method;
use HttpClientBinder\Provider\MethodsProvider;
use ReflectionClass;
use Reflector;

final class MapFromAnnotation implements MappingFactory
{
    /**
     * @var ClientProvider
     */
    private $clientProvider;

    /**
     * @var MethodsProvider
     */
    private $methodsProvider;

    public function __construct(ClientProvider $clientProvider, MethodsProvider $methodsProvider)
    {
        $this->clientProvider = $clientProvider;
        $this->methodsProvider = $methodsProvider;
    }

    public function build(): Client
    {
        $endpoints = array_map(function (Method $method) {
            return
                new Endpoint(
                    $method->getName(),
                    HttpMethod::fromValue($method->getRequest()->getMethod()),
                    new Url(
                        $method->getRequest()->getUri(),
                        $this->parameterBagToUrlParameterBag($method)
                    ),
                    $this->headerBagToHttpHeaderBag($method),
                    $this->createResponseBody($method),
                    $this->createRequestBody($method)
                );
        }, $this->methodsProvider->provide());

        $client = $this->clientProvider->provide();

        return new Client($client->getBaseUrl(), new EndpointBag($endpoints));
    }

    private function parameterBagToUrlParameterBag(Method $method): UrlParameterBag
    {
        return
            new UrlParameterBag(
                array_map(
                    function (Parameter $parameter) {
                        return new UrlParameter($parameter->getArgumentName(), $parameter->getAlias());
                    },
                    $method->getParametersBag()->getParameters()
                )
            );
    }

    private function headerBagToHttpHeaderBag(Method $method): HttpHeaderBag
    {
        return
            new HttpHeaderBag(
                array_map(
                    function (Header $header) {
                        return new HttpHeader($header->getValue());
                    },
                    $method->getHeadersBag()->getHeaders()
                )
            );
    }

    private function createResponseBody(Method $method): ResponseBody
    {
        return
            new ResponseBody(
                $method->getResponseType(),
                SerializationType::JSON()
            );
    }

    private function createRequestBody(Method $method): ?RequestBody
    {
        if (null === $method->getRequestBody()) {
            return null;
        }

        return
            new RequestBody(
                $method->getRequestType(),
                $method->getRequestBody()->getArgumentName(),
                SerializationType::JSON()
            );
    }
}