<?php

declare(strict_types=1);

namespace HttpClientBinder\Fabrics\Protocol;

use HttpClientBinder\Codec\DecoderInterface;
use HttpClientBinder\Codec\EncoderInterface;
use HttpClientBinder\Fabrics\RemoteCall\RemoteCallFactory;
use HttpClientBinder\Fabrics\RemoteCall\RemoteCallFactoryInterface;
use HttpClientBinder\Mapping\Dto\MappingClient;
use HttpClientBinder\Protocol\MagicProtocol;
use HttpClientBinder\Protocol\MagicProtocolFactoryInterface;
use HttpClientBinder\Protocol\MagicProtocolInterface;
use HttpClientBinder\Protocol\RemoteCall\RemoteCallInterface;
use HttpClientBinder\Protocol\RemoteCall\RequestInterceptorInterface;
use HttpClientBinder\Protocol\RemoteCallStorageInterface;
use JMS\Serializer\SerializerInterface;

final readonly class MagicProtocolFactory implements MagicProtocolFactoryInterface
{
    public function __construct(
        private SerializerInterface $serializer,
        private EncoderInterface $encoder,
        private DecoderInterface $decoder,
        private RequestInterceptorInterface $requestInterceptor,
        private ?string $baseUrl = null
    ) {
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
                /** @var RemoteCallInterface[] */
                private array $remoteCalls = [];

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
        return new RemoteCallFactory(
            $this->encoder,
            $this->decoder,
            $this->requestInterceptor,
            $this->baseUrl
        );
    }
}