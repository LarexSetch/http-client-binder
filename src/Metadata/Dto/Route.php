<?php

declare(strict_types=1);

namespace HttpClientBinder\Metadata\Dto;

use HttpClientBinder\Enums\HttpMethod;

final readonly class Route
{
    public function __construct(
        public HttpMethod $method,
        public string $pathPattern,
        /** @var array<string, EndpointArgument> key - is path pattern or query key, value is argument name */
        public array $parameters = [],
    ) {
    }
}
