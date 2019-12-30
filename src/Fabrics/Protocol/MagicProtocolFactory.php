<?php

declare(strict_types=1);

namespace HttpClientBinder\Fabrics\Protocol;

use HttpClientBinder\Codec\DecoderInterface;
use HttpClientBinder\Codec\EncoderInterface;
use HttpClientBinder\Fabrics\RemoteCall\RemoteCallFactoryInterface;
use HttpClientBinder\Mapping\Dto\MappingClient;
use HttpClientBinder\Protocol\MagicProtocol;
use HttpClientBinder\Protocol\MagicProtocolFactoryInterface;
use HttpClientBinder\Protocol\MagicProtocolInterface;
use HttpClientBinder\Fabrics\RemoteCall\RemoteCallFactory;
use HttpClientBinder\Protocol\RemoteCall\RemoteCallInterface;
use HttpClientBinder\Protocol\RemoteCallStorageInterface;
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
        $mappingClient = $this->getMappingClient($jsonMappings);

        return
            new MagicProtocol(
                $mappingClient,
                $this->getRemoteCallStorage($mappingClient)
            );
    }

    private function getMappingClient(string $mappings): MappingClient
    {
        return $this->serializer->deserialize($mappings, MappingClient::class, 'json');
    }

    private function getRemoteCallStorage(MappingClient $client): RemoteCallStorageInterface
    {
        $storage = self::getStorageInstance();
        $remoteCallFactory = $this->getRemoteCallFactory();

        foreach ($client->getEndpointBag()->getEndpoints() as $endpoint) {
            $storage->set($endpoint->getName(), $remoteCallFactory->build($client, $endpoint));
        }

        return $storage;
    }

    private static function getStorageInstance(): RemoteCallStorageInterface
    {
        return
            new class implements RemoteCallStorageInterface {
                /**
                 * @var RemoteCallInterface
                 */
                private $remoteCalls;

                public function set(string $name, RemoteCallInterface $remoteCall): RemoteCallStorageInterface
                {
                    $this->remoteCalls[$name] = $remoteCall;

                    return $this;
                }

                public function get(string $name): RemoteCallInterface
                {
                    return $this->remoteCalls[$name];
                }
            };
    }

    private function getRemoteCallFactory(): RemoteCallFactoryInterface
    {
        return
            new RemoteCallFactory(
                $this->serializer,
                $this->encoder,
                $this->decoder,
                $this->requestInterceptor,
                $this->baseUrl
            );
    }
}