<?php

declare(strict_types=1);

namespace HttpClientBinder\Proxy;

use HttpClientBinder\Metadata\Dto\Endpoint;
use HttpClientBinder\Metadata\Dto\Route;
use HttpClientBinder\Metadata\Dto\HttpHeader;
use HttpClientBinder\Metadata\Dto\EndpointArgument;

class EndpointRenderer
{
    public static function render(Endpoint $endpoint): string
    {
        return sprintf(
            "new \HttpClientBinder\Metadata\Dto\Endpoint(\n" .
            "    route: %s,\n" .
            "    name: '%s',\n" .
            "    headers: [%s],\n" .
            "    arguments: [%s],\n" .
            "    resultType: '%s',\n" .
            "    requestBody: %s\n" .
            ")",
            self::renderRoute($endpoint->route),
            $endpoint->name,
            self::renderHeaders($endpoint->headers),
            self::renderArguments($endpoint->arguments),
            $endpoint->resultType,
            $endpoint->requestBody ? self::renderArgument($endpoint->requestBody) : 'null'
        );
    }

    private static function renderRoute(Route $route): string
    {
        return sprintf(
            "new \HttpClientBinder\Metadata\Dto\Route(\n" .
            "        method: \HttpClientBinder\Enums\HttpMethod::%s,\n" .
            "        pathPattern: '%s',\n" .
            "        parameters: [%s]\n" .
            "    )",
            $route->method->name,
            $route->pathPattern,
            self::renderParameters($route->parameters)
        );
    }

    private static function renderHeaders(array $headers): string
    {
        return implode(
            ", ",
            array_map(
                fn (HttpHeader $header) => sprintf(
                    "new \HttpClientBinder\Metadata\Dto\HttpHeader(name: '%s', value: '%s', parameters: [%s])",
                    $header->name,
                    $header->value,
                    self::renderParameters($header->parameters)
                ),
                $headers
            )
        );
    }

    private static function renderArguments(array $arguments): string
    {
        return implode(
            ", ",
            array_map(
                fn (EndpointArgument $argument) => self::renderArgument($argument),
                $arguments
            )
        );
    }

    private static function renderArgument(EndpointArgument $argument): string
    {
        return sprintf(
            "new \HttpClientBinder\Metadata\Dto\EndpointArgument(position: %d, name: '%s', type: '%s')",
            $argument->position,
            $argument->name,
            $argument->type
        );
    }

    /**
     * @param array<string, EndpointArgument> $parameters
     */
    private static function renderParameters(array $parameters): string
    {
        $rendered = [];
        foreach ($parameters as $key => $argument) {
            $rendered[] = sprintf("'%s' => %s", $key, self::renderArgument($argument));
        }

        return implode(", ", $rendered);
    }
}
