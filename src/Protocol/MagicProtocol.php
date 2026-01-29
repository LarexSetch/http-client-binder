<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol;

use HttpClientBinder\Metadata\Dto\ClientMetadata;
use HttpClientBinder\Metadata\Dto\Endpoint;
use HttpClientBinder\Protocol\RemoteCall\RemoteCall;

final readonly class MagicProtocol implements MagicProtocolInterface
{
    public function __construct(
        private ClientMetadata $client,
        private RemoteCall $remoteCallStorage
    ) {
    }

    /**
     * @throws UnexpectedEndpointException
     */
    public function __call(string $name, array $arguments)
    {
        $endpoint = $this->getEndpoint($name);
        $remoteCall = $this->remoteCallStorage->get($name);

        return $remoteCall->invoke($this->requestBuilder->build($endpoint, self::mapArguments($endpoint, $arguments)));
    }

    /**
     * @throws UnexpectedEndpointException
     */
    private function getEndpoint(string $name): Endpoint
    {
        foreach ($this->client->endpoints as $endpoint) {
            if ($endpoint->name === $name) {
                return $endpoint;
            }
        }

        throw new UnexpectedEndpointException(sprintf('Endpoint with name %s not found', $name));
    }

    private static function mapArguments(Endpoint $endpoint, array $arguments): array
    {
        if (count($arguments) !== count($endpoint->arguments)) {
            throw new UnexpectedEndpointException('Arguments of endpoint must have the same number of argument method');
        }
        $map = [];
        foreach ($arguments as $i => $argument) {
            $map[$endpoint->arguments[$i]->name] = $argument;
        }

        return $map;
    }
}