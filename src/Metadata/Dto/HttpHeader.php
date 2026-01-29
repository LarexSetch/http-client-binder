<?php

declare(strict_types=1);

namespace HttpClientBinder\Metadata\Dto;

final readonly class HttpHeader
{
    public function __construct(
        public string $name,
        public string $value,
        /** @var array<string, EndpointArgument> key - is path pattern or query key, value is argument name */
        public array $parameters = []
    ) {
    }
}