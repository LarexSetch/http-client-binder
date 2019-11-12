<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\RemoteCall;

use HttpClientBinder\Mapping\Dto\Client;
use HttpClientBinder\Mapping\Dto\Endpoint;

interface RemoteCallFactory
{
    public function build(Client $client, Endpoint $endpoint): RemoteCall;
}