<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol;

use HttpClientBinder\Mapping\Dto\Client;
use HttpClientBinder\Mapping\Dto\Endpoint;
use HttpClientBinder\Protocol\RemoteCall\RemoteCallFactory;
use HttpClientBinder\Protocol\RequestBuilder\RequestBuilder;
use HttpClientBinder\Protocol\ResponseAssembler\ResponseAssembler;

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
     * @var ResponseAssembler
     */
    private $responseAssembler;

    public function __construct(
        Client $client,
        RemoteCallFactory $remoteCallFactory,
        RequestBuilder $requestBuilder,
        ResponseAssembler $responseAssembler
    ) {
        $this->client = $client;
        $this->remoteCallFactory = $remoteCallFactory;
        $this->requestBuilder = $requestBuilder;
        $this->responseAssembler = $responseAssembler;
    }

    /**
     * @throws UnexpectedEndpointException
     */
    public function __call(string $name, array $arguments)
    {
        $endpoint = $this->getEndpoint($name);
        $remoteCall = $this->remoteCallFactory->build($this->client, $endpoint);
        $response = $remoteCall->invoke($this->requestBuilder->build($endpoint, $arguments));

        return $this->responseAssembler->assemble($response, $endpoint);
    }

    /**
     * @throws UnexpectedEndpointException
     */
    private function getEndpoint(string $name): Endpoint
    {
        foreach($this->client->getEndpointBag()->getEndpoints() as $endpoint){
            if($endpoint->getName() === $name) {
                return $endpoint;
            }
        }

        throw new UnexpectedEndpointException(sprintf('Endpoint with name %s not found', $name));
    }
}