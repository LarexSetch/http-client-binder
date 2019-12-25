<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\RemoteCall;

use HttpClientBinder\Mapping\Dto\MappingClient;
use HttpClientBinder\Mapping\Dto\Endpoint;

interface RemoteCallFactoryInterface
{
    public function build(MappingClient $client, Endpoint $endpoint): RemoteCallInterface;
}