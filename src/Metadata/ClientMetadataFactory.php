<?php

declare(strict_types=1);

namespace HttpClientBinder\Metadata;

use HttpClientBinder\Metadata\Dto\ClientMetadata;

interface ClientMetadataFactory
{
    public function create(string $interfaceName, string $baseUrl): ClientMetadata;
}