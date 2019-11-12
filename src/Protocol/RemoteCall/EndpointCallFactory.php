<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\RemoteCall;

use GuzzleHttp\Client as GuzzleClient;
use HttpClientBinder\Mapping\Dto\Client;
use HttpClientBinder\Mapping\Dto\Endpoint;

final class EndpointCallFactory implements RemoteCallFactory
{
    public function build(Client $client, Endpoint $endpoint): RemoteCall
    {
        return
            new EndpointCall(
                new GuzzleClient(
                    ['base_uri' => $client->getBaseUrl()]
                )
            );
    }
}