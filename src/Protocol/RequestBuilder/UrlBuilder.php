<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\RequestBuilder;

use HttpClientBinder\Mapping\Dto\Endpoint;
use HttpClientBinder\Mapping\Dto\UrlParameter;

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
            if (UrlParameter::TYPE_PATH === $parameter->getType()) {
                $this->checkArgument($parameter, $arguments);
                $key = sprintf('{%s}', $parameter->getAlias() ?? $parameter->getArgument());
                $replaceParameters[$key] = $arguments[$parameter->getArgumentIndex()];
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
            if (UrlParameter::TYPE_QUERY === $parameter->getType()) {
                $this->checkArgument($parameter, $arguments);
                $key = $parameter->getAlias() ?? $parameter->getArgument();
                $queryParameters[$key] = $arguments[$parameter->getArgumentIndex()];
            }
        }

        return $queryParameters;
    }

    private function checkArgument(UrlParameter $parameter, array $arguments): void
    {
        if(!key_exists($parameter->getArgumentIndex(), $arguments)) {
            throw new \DomainException(sprintf(
                "Cannot find argument on position %s for parameter %s",
                $parameter->getArgumentIndex(),
                $parameter->getArgument()
            ));
        }

        if(!is_scalar($arguments[$parameter->getArgumentIndex()])) {
            throw new \DomainException(sprintf(
                "Argument must be scalar on position %s name %s",
                $parameter->getArgumentIndex(),
                $parameter->getArgument()
            ));
        }
    }
}