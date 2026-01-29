<?php

declare(strict_types=1);

namespace HttpClientBinder\Metadata\Dto;

final readonly class ClientMetadata
{
    public function __construct(
        public string $name,
        /** @var Endpoint[] */
        public array $endpoints,
        /** @var HttpHeader[] */
        public array $headers,
        public string $baseUrl
    ) {
    }
}