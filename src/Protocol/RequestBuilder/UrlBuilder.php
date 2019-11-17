<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\RequestBuilder;

use HttpClientBinder\Mapping\Dto\Endpoint;
use HttpClientBinder\Mapping\Dto\UrlParameter;
use HttpClientBinder\Mapping\Enum\UrlParameterType;

final class UrlBuilder implements UrlBuilderInterface
{
    public function build(Endpoint $endpoint, array $arguments): string
    {
        return
            strtr($endpoint->getUrl()->getValue(), $this->getReplaceParameters($endpoint, $arguments)) .
            $this->getQuery($endpoint, $arguments);
    }

    private function getReplaceParameters(Endpoint $endpoint, array $arguments): array
    {
        $replaceParameters = [];
        foreach ($endpoint->getUrl()->getParameterBag()->getParameters() as $parameter) {
            if ($this->isValid($parameter, $arguments) && UrlParameterType::PATH() === $parameter->getType()) {
                $key = sprintf('{%s}', $parameter->getName());
                $replaceParameters[$key] = $arguments[$parameter->getName()];
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
        foreach ($endpoint->getUrl()->getParameterBag()->getParameters() as $parameter) {
            if ($this->isValid($parameter, $arguments) && UrlParameterType::QUERY() === $parameter->getType()) {
                $queryParameters[$parameter->getName()] = $arguments[$parameter->getName()];
            }
        }

        return $queryParameters;
    }

    private function isValid(UrlParameter $parameter, array $arguments): bool
    {
        return
            key_exists($parameter->getName(), $arguments) &&
            is_scalar($arguments[$parameter->getName()]);
    }
}