<?php

declare(strict_types=1);

namespace HttpClientBinder\Proxy\RequestFactory;

use DomainException;
use HttpClientBinder\Metadata\Dto\Route;

final class UrlBuilder
{
    public static function build(Route $route, array $arguments): string
    {
        [$pathParameters, $queryParameters] = self::collectParameters($route, $arguments);

        return
            strtr($route->pathPattern, $pathParameters)
            . (empty($queryParameters) ? '' : '?' . http_build_query($queryParameters));
    }

    private static function collectParameters(Route $route, array $arguments): array
    {
        $pathParameters = [];
        $queryParameters = [];
        foreach ($route->parameters as $parameterKey => $endpointArgument) {
            if (!key_exists($endpointArgument->position, $arguments)) {
                throw new DomainException(
                    sprintf(
                        'Argument on position "%s" does not exist in route %s %s',
                        $endpointArgument->position,
                        $route->method->name,
                        $route->pathPattern
                    )
                );
            }

            if (str_contains($route->pathPattern, $parameterKey)) {
                $pathParameters[$parameterKey] = $arguments[$endpointArgument->position];
            } else {
                $queryParameters[$parameterKey] = $arguments[$endpointArgument->position];
            }
        }

        return [$pathParameters, $queryParameters];
    }
}