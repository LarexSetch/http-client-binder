<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol;

use HttpClientBinder\Mapping\Dto\Client;
use HttpClientBinder\Protocol\RemoteCall\EndpointCallFactory;
use HttpClientBinder\Protocol\RequestBuilder\BodyResolver\BodyResolverStrategy;
use HttpClientBinder\Protocol\RequestBuilder\GuzzleRequestBuilder;
use HttpClientBinder\Protocol\RequestBuilder\UrlResolver\UrlResolverStrategy;
use HttpClientBinder\Protocol\ResponseAssembler\Deserialize;
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
                new EndpointCallFactory(),
                new GuzzleRequestBuilder(
                    new UrlResolverStrategy(),
                    new BodyResolverStrategy($this->serializer)
                ),
                new Deserialize()
            );
    }

    private function deserializeMappings(string $mappings): Client
    {
        return $this->serializer->deserialize($mappings, 'json', Client::class);
    }
}