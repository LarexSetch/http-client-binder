<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\RemoteCall;

use HttpClientBinder\Mapping\Dto\Client;
use HttpClientBinder\Mapping\Dto\Endpoint;

interface RemoteCallFactoryInterface
{
    public function build(Client $client, Endpoint $endpoint): RemoteCallInterface;
}