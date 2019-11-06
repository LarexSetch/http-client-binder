<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol;

use HttpClientBinder\Mapping\Dto\Client;
use HttpClientBinder\Mapping\Dto\Endpoint;
use HttpClientBinder\Protocol\RequestBuilder\RequestBuilder;
use HttpClientBinder\Protocol\ResponseAdapter\ResponseAdapter;

final class MagicProtocol
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var RemoteCallFactory
     */
    private $remoteCallFactory;

    /**
     * @var RequestBuilder
     */
    private $requestBuilder;

    /**
     * @var ResponseAdapter
     */
    private $responseAdapter;

    public function __construct(
        Client $client,
        RemoteCallFactory $remoteCallFactory,
        RequestBuilder $requestBuilder,
        ResponseAdapter $responseAdapter
    ) {
        $this->client = $client;
        $this->remoteCallFactory = $remoteCallFactory;
        $this->requestBuilder = $requestBuilder;
        $this->responseAdapter = $responseAdapter;
    }

    public function __call(string $name, array $arguments)
    {
        $endpoint = $this->getEndpoint($name);
        $remoteCall = $this->remoteCallFactory->build($this->client, $endpoint);
        $response = $remoteCall->invoke($this->requestBuilder->build($endpoint, $arguments));

        return $this->responseAdapter->assemble($response, $endpoint);
    }

    private function getEndpoint(string $name): Endpoint
    {
        foreach($this->client->getEndpointBag()->getEndpoints() as $endpoint){
            if($endpoint->getName() === $name) {
                return $endpoint;
            }
        }

        // todo throw exception
    }
}