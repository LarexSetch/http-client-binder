<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\RequestBuilder\UrlResolver;

use HttpClientBinder\Mapping\Dto\Endpoint;
use HttpClientBinder\Mapping\Dto\UrlParameter;
use HttpClientBinder\Mapping\Enum\UrlParameterType;

final class UrlResolverStrategy implements UrlResolver
{
    public function resolve(Endpoint $endpoint, array $arguments): string
    {
        return
            strtr($endpoint->getUrl(), $this->getReplaceParameters($endpoint, $arguments)) .
            $this->getQuery($endpoint, $arguments);
    }

    private function getReplaceParameters(Endpoint $endpoint, array $arguments): array
    {
        $replaceParameters = [];
        foreach ($endpoint->getParameterBag()->getParameters() as $parameter) {
            if ($this->isValid($parameter, $arguments) && UrlParameterType::PATH() === $parameter->getType()) {
                $key = sprintf('{%s}', $parameter->getKey());
                $replaceParameters[$key] = $arguments[$parameter->getKey()];
            }
        }

        return $replaceParameters;
    }

    private function getQuery(Endpoint $endpoint, array $arguments): string
    {
        $queryParameters = $this->getQueryParameters($endpoint, $arguments);

        return
            empty($queryParameters)
                ? ''
                : '?' . http_build_query($queryParameters);
    }

    private function getQueryParameters(Endpoint $endpoint, array $arguments): array
    {
        $queryParameters = [];
        foreach ($endpoint->getParameterBag()->getParameters() as $parameter) {
            if ($this->isValid($parameter, $arguments) && UrlParameterType::QUERY() === $parameter->getType()) {
                $queryParameters[$parameter->getKey()] = $arguments[$parameter->getKey()];
            }
        }

        return $queryParameters;
    }

    private function isValid(UrlParameter $parameter, array $arguments): bool
    {
        return
            key_exists($parameter->getKey(), $arguments) &&
            is_scalar($arguments[$parameter->getKey()]);
    }
}