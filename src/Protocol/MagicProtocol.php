<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol;

use HttpClientBinder\Mapping\Dto\MappingClient;
use HttpClientBinder\Mapping\Dto\Endpoint;

final class MagicProtocol implements MagicProtocolInterface
{
    /**
     * @var MappingClient
     */
    private $client;

    /**
     * @var RemoteCallStorageInterface
     */
    private $remoteCallStorage;

    public function __construct(
        MappingClient $client,
        RemoteCallStorageInterface $remoteCallStorage
    ) {
        $this->client = $client;
        $this->remoteCallStorage = $remoteCallStorage;
    }

    /**
     * @throws UnexpectedEndpointException
     */
    public function __call(string $name, array $arguments)
    {
        $endpoint = $this->getEndpoint($name);
        $remoteCall = $this->remoteCallStorage->get($name);

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