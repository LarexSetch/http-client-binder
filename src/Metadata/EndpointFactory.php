<?php

declare(strict_types=1);

namespace HttpClientBinder\Metadata;

use HttpClientBinder\Annotation\Header;
use HttpClientBinder\Annotation\HeaderParameter;
use HttpClientBinder\Annotation\PathParameter;
use HttpClientBinder\Annotation\RequestBody;
use HttpClientBinder\Annotation\RequestMapping;
use HttpClientBinder\Metadata\Dto\Endpoint;
use HttpClientBinder\Metadata\Dto\EndpointArgument;
use HttpClientBinder\Metadata\Dto\HttpHeader;
use HttpClientBinder\Metadata\Dto\Route;
use ReflectionMethod;

class EndpointFactory
{
    public static function create(ReflectionMethod $method): Endpoint
    {
        $requestMapping = null;
        $requestParameters = [];
        $headers = [];
        $arguments = [];
        $requestBody = null;
        foreach ($method->getAttributes() as $reflectionAttribute) {
            $attributeInstance = $reflectionAttribute->newInstance();
            if ($attributeInstance instanceof RequestMapping) {
                $requestMapping = $attributeInstance;
                if ($requestMapping->responseType !== null) {
                    $headers[] = new HttpHeader('Accept', $requestMapping->responseType);
                }
            }

            if ($attributeInstance instanceof Header) {
                $headers[] = new HttpHeader(
                    $attributeInstance->header,
                    $attributeInstance->getValue()
                );
            }
        }

        foreach ($method->getParameters() as $reflectionParameter) {
            $endpointArgument = new EndpointArgument(
                $reflectionParameter->getPosition(),
                $reflectionParameter->getName(),
                $reflectionParameter->getType()->getName(),
            );
            foreach ($reflectionParameter->getAttributes() as $attributeInstance) {
                $attributeInstance = $attributeInstance->newInstance();
                if ($attributeInstance instanceof HeaderParameter) {
                    $headerKey = sprintf('{%s}', $reflectionParameter->getName());
                    $headers[] = new HttpHeader(
                        $attributeInstance->name,
                        $attributeInstance->pattern === null ? $headerKey : $attributeInstance->pattern,
                        [$headerKey => $endpointArgument]
                    );
                }

                if ($attributeInstance instanceof PathParameter) {
                    $requestParameters[$attributeInstance->name] = $endpointArgument;
                }

                if ($attributeInstance instanceof RequestBody) {
                    $requestBody = $endpointArgument;
                    $headers[] = new HttpHeader('Content-Type', $attributeInstance->contentType);
                }
            }
            $arguments[] = $endpointArgument;
        }

        return new Endpoint(
            route: new Route(
                $requestMapping->method,
                $requestMapping->uri,
                $requestParameters
            ),
            name: $method->getName(),
            headers: $headers,
            arguments: $arguments,
            resultType: $method->getReturnType()->getName(),
            requestBody: $requestBody,
        );
    }
}
