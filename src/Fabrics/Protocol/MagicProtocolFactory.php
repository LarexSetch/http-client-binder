<?php

declare(strict_types=1);

namespace HttpClientBinder\Fabrics\Protocol;

use HttpClientBinder\Codec\DecoderInterface;
use HttpClientBinder\Codec\EncoderInterface;
use HttpClientBinder\Mapping\Dto\MappingClient;
use HttpClientBinder\Protocol\MagicProtocol;
use HttpClientBinder\Protocol\MagicProtocolInterface;
use HttpClientBinder\Fabrics\RemoteCall\RemoteCallFactory;
use HttpClientBinder\Protocol\RemoteCall\RequestInterceptorInterface;
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

    /**
     * @var RequestInterceptorInterface
     */
    private $requestInterceptor;

    /**
     * @var string|null
     */
    private $baseUrl;

    public function __construct(
        SerializerInterface $serializer,
        EncoderInterface $encoder,
        DecoderInterface $decoder,
        RequestInterceptorInterface $requestInterceptor,
        ?string $baseUrl = null
    ) {
        $this->serializer = $serializer;
        $this->encoder = $encoder;
        $this->decoder = $decoder;
        $this->requestInterceptor = $requestInterceptor;
        $this->baseUrl = $baseUrl;
    }

    public function build(string $jsonMappings): MagicProtocolInterface
    {
        return
            new MagicProtocol(
                $this->deserializeMappings($jsonMappings),
                new RemoteCallFactory(
                    $this->serializer,
                    $this->encoder,
                    $this->decoder,
                    $this->requestInterceptor,
                    $this->baseUrl
                )
            );
    }

    private function deserializeMappings(string $mappings): MappingClient
    {
        return $this->serializer->deserialize($mappings, MappingClient::class, 'json');
    }
}