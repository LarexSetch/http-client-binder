<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol;

use HttpClientBinder\Codec\DecoderInterface;
use HttpClientBinder\Mapping\Dto\MappingClient;
use HttpClientBinder\Mapping\Dto\Endpoint;
use HttpClientBinder\Fabrics\RemoteCall\RemoteCallFactoryInterface;

final class MagicProtocol implements MagicProtocolInterface
{
    /**
     * @var MappingClient
     */
    private $client;

    /**
     * @var RemoteCallFactoryInterface
     */
    private $remoteCallFactory;

    public function __construct(
        MappingClient $client,
        RemoteCallFactoryInterface $remoteCallFactory
    ) {
        $this->client = $client;
        $this->remoteCallFactory = $remoteCallFactory;
    }

    /**
     * @throws UnexpectedEndpointException
     */
    public function __call(string $name, array $arguments)
    {
        $endpoint = $this->getEndpoint($name);
        $remoteCall = $this->remoteCallFactory->build($this->client, $endpoint);

        return $remoteCall->invoke($endpoint, $arguments);
    }

    /**
     * @throws UnexpectedEndpointException
     */
    private function getEndpoint(string $name): Endpoint
    {
        foreach ($this->client->getEndpointBag()->getEndpoints() as $endpoint) {
            if ($endpoint->getName() === $name) {
                return $endpoint;
            }
        }

        throw new UnexpectedEndpointException(sprintf('Endpoint with name %s not found', $name));
    }
}