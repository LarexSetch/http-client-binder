<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol;

use HttpClientBinder\Codec\DecoderInterface;
use HttpClientBinder\Codec\TypeBuilderInterface;
use HttpClientBinder\Mapping\Dto\Client;
use HttpClientBinder\Mapping\Dto\Endpoint;
use HttpClientBinder\Protocol\RemoteCall\RemoteCallFactoryInterface;
use HttpClientBinder\Protocol\RequestBuilder\RequestBuilder;
use HttpClientBinder\Protocol\ResponseDecoder\ResponseTypeBuilderFactoryInterface;

final class MagicProtocol
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var RemoteCallFactoryInterface
     */
    private $remoteCallFactory;

    /**
     * @var RequestBuilder
     */
    private $requestBuilder;

    /**
     * @var DecoderInterface
     */
    private $decoder;

    /**
     * @var ResponseTypeBuilderFactoryInterface
     */
    private $responseTypeBuilderFactory;

    public function __construct(
        Client $client,
        RemoteCallFactoryInterface $remoteCallFactory,
        RequestBuilder $requestBuilder,
        DecoderInterface $decoder,
        ResponseTypeBuilderFactoryInterface $responseTypeBuilderFactory
    ) {
        $this->client = $client;
        $this->remoteCallFactory = $remoteCallFactory;
        $this->requestBuilder = $requestBuilder;
        $this->decoder = $decoder;
        $this->responseTypeBuilderFactory = $responseTypeBuilderFactory;
    }

    /**
     * @throws UnexpectedEndpointException
     */
    public function __call(string $name, array $arguments)
    {
        $endpoint = $this->getEndpoint($name);
        $remoteCall = $this->remoteCallFactory->build($this->client, $endpoint);
        $response = $remoteCall->invoke($this->requestBuilder->build($endpoint, $arguments));

        return
            $this->decoder->decode(
                $response->getBody(),
                $this->responseTypeBuilderFactory->create($response)->build($endpoint)
            );
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