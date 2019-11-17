<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\RemoteCall;

use GuzzleHttp\Client as GuzzleClient;
use HttpClientBinder\Mapping\Dto\Client;
use HttpClientBinder\Mapping\Dto\Endpoint;

final class RemoteCallFactory implements RemoteCallFactoryInterface
{
    public function build(Client $client, Endpoint $endpoint): RemoteCallInterface
    {
        return
            new RemoteCall(
                new GuzzleClient(
                    ['base_uri' => $client->getBaseUrl()]
                )
            );
    }
}