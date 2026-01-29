<?php

declare(strict_types=1);

namespace HttpClientBinder\Metadata\Dto;

final readonly class EndpointArgument
{
    public function __construct(
        public int $position,
        public string $name,
        public string $type,
    ) {
    }
}
