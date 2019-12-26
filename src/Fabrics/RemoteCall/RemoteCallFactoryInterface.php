<?php

declare(strict_types=1);

namespace HttpClientBinder\Fabrics\RemoteCall;

use HttpClientBinder\Mapping\Dto\MappingClient;
use HttpClientBinder\Mapping\Dto\Endpoint;
use HttpClientBinder\Protocol\RemoteCall\RemoteCallInterface;

interface RemoteCallFactoryInterface
{
    public function build(MappingClient $client, Endpoint $endpoint): RemoteCallInterface;
}