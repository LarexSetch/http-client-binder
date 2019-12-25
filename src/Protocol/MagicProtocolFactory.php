<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol;

use HttpClientBinder\Codec\DecoderInterface;
use HttpClientBinder\Codec\EncoderInterface;
use HttpClientBinder\Mapping\Dto\MappingClient;
use HttpClientBinder\Protocol\RemoteCall\RemoteCallFactory;
use JMS\Serializer\SerializerInterface;

final class MagicProtocolFactory implements MagicProtocolFactoryInterface
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

    public function build(string $jsonMappings): MagicProtocol
    {
        return
            new MagicProtocol(
                $this->deserializeMappings($jsonMappings),
                new RemoteCallFactory(
                    $this->serializer,
                    $this->encoder,
                    $this->decoder
                )
            );
    }

    private function deserializeMappings(string $mappings): MappingClient
    {
        return $this->serializer->deserialize($mappings, MappingClient::class, 'json');
    }
}