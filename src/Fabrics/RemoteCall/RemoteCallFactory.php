<?php

declare(strict_types=1);

namespace HttpClientBinder\Fabrics\RemoteCall;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use HttpClientBinder\Codec\DecoderInterface;
use HttpClientBinder\Codec\EncoderInterface;
use HttpClientBinder\Mapping\Dto\MappingClient;
use HttpClientBinder\Mapping\Dto\Endpoint;
use HttpClientBinder\Protocol\RemoteCall\RemoteCall;
use HttpClientBinder\Protocol\RemoteCall\RemoteCallInterface;
use HttpClientBinder\Protocol\RemoteCall\RequestBuilder\BodyResolver;
use HttpClientBinder\Protocol\RemoteCall\RequestBuilder\GuzzleRequestBuilder;
use HttpClientBinder\Protocol\RemoteCall\RequestBuilder\RequestTypeBuilder;
use HttpClientBinder\Protocol\RemoteCall\RequestBuilder\StreamBuilder;
use HttpClientBinder\Protocol\RemoteCall\RequestBuilder\UrlBuilder;
use JMS\Serializer\SerializerInterface;

final class RemoteCallFactory implements RemoteCallFactoryInterface
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var EncoderInterface
     */
    private $encoder;

    /**
     * @var DecoderInterface
     */
    private $decoder;

    /**
     * @var string|null
     */
    private $baseUrl;

    public function __construct(
        SerializerInterface $serializer,
        EncoderInterface $encoder,
        DecoderInterface $decoder,
        ?string $baseUrl = null
    ) {
        $this->serializer = $serializer;
        $this->encoder = $encoder;
        $this->decoder = $decoder;
        $this->baseUrl = $baseUrl;
    }

    public function build(MappingClient $client, Endpoint $endpoint): RemoteCallInterface
    {
        return
            new RemoteCall(
                $this->createGuzzleClient($client),
                new GuzzleRequestBuilder(
                    new UrlBuilder(),
                    new BodyResolver(
                        new StreamBuilder(),
                        $this->encoder,
                        new RequestTypeBuilder()
                    )
                ),
                $this->decoder
            );
    }

    private function createGuzzleClient(MappingClient $client): ClientInterface
    {
        $baseUrl = $this->baseUrl ?? $client->getBaseUrl();
        if (null === $baseUrl) {
            throw new \DomainException("You must define base url for client");
        }

        return
            new GuzzleClient(
                ['base_uri' => $baseUrl]
            );
    }
}