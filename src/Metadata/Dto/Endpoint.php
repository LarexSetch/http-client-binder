<?php

declare(strict_types=1);

namespace HttpClientBinder\Metadata\Dto;

final readonly class Endpoint
{
    public function __construct(
        public Route $route,
        public string $name,
        /** @var array<HttpHeader> */
        public array $headers,
        /** @var array<EndpointArgument> */
        public array $arguments,
        public string $resultType,
        /** Argument name of request body if presents */
        public ?EndpointArgument $requestBody = null,
    ) {
    }
}