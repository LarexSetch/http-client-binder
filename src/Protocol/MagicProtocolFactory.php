<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol;

use HttpClientBinder\Mapping\Dto\Client;
use HttpClientBinder\Protocol\RemoteCall\RemoteCallFactory;
use HttpClientBinder\Protocol\RequestBuilder\BodyEncoder;
use HttpClientBinder\Protocol\RequestBuilder\BodyResolver;
use HttpClientBinder\Protocol\RequestBuilder\GuzzleRequestBuilder;
use HttpClientBinder\Protocol\RequestBuilder\RequestTypeBuilder;
use HttpClientBinder\Protocol\RequestBuilder\StreamBuilder;
use HttpClientBinder\Protocol\RequestBuilder\UrlBuilder;
use HttpClientBinder\Protocol\ResponseDecoder\ResponseDecoder;
use HttpClientBinder\Protocol\ResponseDecoder\ResponseTypeBuilderFactory;
use JMS\Serializer\SerializerInterface;

final class MagicProtocolFactory implements MagicProtocolFactoryInterface
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function build(string $jsonMappings): MagicProtocol
    {
        return
            new MagicProtocol(
                $this->deserializeMappings($jsonMappings),
                new RemoteCallFactory(),
                new GuzzleRequestBuilder(
                    new UrlBuilder(),
                    new BodyResolver(
                        $this->serializer,
                        new StreamBuilder(),
                        new BodyEncoder(
                            $this->serializer,
                            new StreamBuilder()
                        ),
                        new RequestTypeBuilder()
                    )
                ),
                new ResponseDecoder(
                    $this->serializer
                ),
                new ResponseTypeBuilderFactory()
            );
    }

    private function deserializeMappings(string $mappings): Client
    {
        return $this->serializer->deserialize($mappings, Client::class, 'json');
    }
}