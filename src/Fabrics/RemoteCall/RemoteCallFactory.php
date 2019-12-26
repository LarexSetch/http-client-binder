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
use HttpClientBinder\Protocol\RequestBuilder\BodyResolver;
use HttpClientBinder\Protocol\RequestBuilder\GuzzleRequestBuilder;
use HttpClientBinder\Protocol\RequestBuilder\RequestTypeBuilder;
use HttpClientBinder\Protocol\RequestBuilder\StreamBuilder;
use HttpClientBinder\Protocol\RequestBuilder\UrlBuilder;
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

    public function __construct(
        SerializerInterface $serializer,
        EncoderInterface $encoder,
        DecoderInterface $decoder
    ) {
        $this->serializer = $serializer;
        $this->encoder = $encoder;
        $this->decoder = $decoder;
    }

    public function build(MappingClient $client, Endpoint $endpoint): RemoteCallInterface
    {
        return
            new RemoteCall(
                $this->createGuzzleClient($client, $endpoint),
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

    private function createGuzzleClient(MappingClient $client, Endpoint $endpoint): ClientInterface
    {
        return
            new GuzzleClient(
                ['base_uri' => $client->getBaseUrl()]
            );
    }
}