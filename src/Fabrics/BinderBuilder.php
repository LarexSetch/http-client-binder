<?php

declare(strict_types=1);

namespace HttpClientBinder\Fabrics;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use HttpClientBinder\Codec\DecoderInterface;
use HttpClientBinder\Codec\EncoderInterface;
use HttpClientBinder\Codec\Type;
use HttpClientBinder\Metadata\Dto\ClientMetadata;
use HttpClientBinder\Metadata\ReflectionClientMetadataFactory;
use HttpClientBinder\Protocol\RemoteCall\GuzzleRemoteCall;
use HttpClientBinder\Protocol\RemoteCall\RequestInterceptorChain;
use HttpClientBinder\Protocol\RemoteCall\RequestInterceptorInterface;
use HttpClientBinder\Proxy\ProxyFactory;
use HttpClientBinder\Proxy\ProxySourceRender;
use HttpClientBinder\Utils\StringToStream;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use Psr\Http\Message\StreamInterface;

/**
 * @template T
 */
final class BinderBuilder
{
    private EncoderInterface $encoder;

    private DecoderInterface $decoder;

    /**
     * @var array<RequestInterceptorInterface>
     */
    private array $requestInterceptors = [];

    private string $tmpDir;

    /**
     * @param class-string<T> $className
     */
    public static function builder(string $className, string $baseUrl): BinderBuilder
    {
        return new self(new ReflectionClientMetadataFactory(), $className, $baseUrl);
    }

    public function encoder(EncoderInterface $encoder): BinderBuilder
    {
        $this->encoder = $encoder;

        return $this;
    }

    public function decoder(DecoderInterface $decoder): BinderBuilder
    {
        $this->decoder = $decoder;

        return $this;
    }

    public function addRequestInterceptor(RequestInterceptorInterface $interceptor): BinderBuilder
    {
        $this->requestInterceptors[] = $interceptor;

        return $this;
    }

    public function temporaryDirectory(string $temporaryDirectory): BinderBuilder
    {
        $this->tmpDir = $temporaryDirectory;

        return $this;
    }

    /**
     * Returns instance of $className in method target
     * @return T
     */
    public function getClient(): mixed
    {
        $metadata = $this->clientMetadataFactory->create($this->className, $this->baseUrl);
        $httpClient = self::createGuzzleClient($metadata);
        $remoteCall = new GuzzleRemoteCall($httpClient, RequestInterceptorChain::create($this->requestInterceptors));
        $proxyFactory = new ProxyFactory(
            new ProxySourceRender(),
            $this->encoder,
            $this->decoder,
            $this->tmpDir,
        );

        return $proxyFactory->create($metadata, $remoteCall);
    }

    private function __construct(
        private readonly ReflectionClientMetadataFactory $clientMetadataFactory,
        private readonly string $className,
        /* @param class-string<T> $className */
        private readonly string $baseUrl

    ) {
        $this->encoder = $this->createDefaultEncoder();
        $this->decoder = $this->createDefaultDecoder();
        $this->tmpDir = "/tmp";
    }

    private function createDefaultEncoder(): EncoderInterface
    {
        return new class implements EncoderInterface {
            private SerializerInterface $serializer;

            public function __construct()
            {
                $this->serializer = SerializerBuilder::create()->build();
            }

            public function encode(mixed $object, Type $type): StreamInterface
            {
                return StringToStream::create($this->serializer->serialize($object, $type->value));
            }
        };
    }

    private function createDefaultDecoder(): DecoderInterface
    {
        return new class implements DecoderInterface {
            private SerializerInterface $serializer;

            public function __construct()
            {
                $this->serializer = SerializerBuilder::create()->build();
            }

            public function decode(StreamInterface $stream, string $className, Type $type): mixed
            {
                return
                    $this->serializer->deserialize(
                        $stream->getContents(),
                        $className,
                        $type->value
                    );
            }
        };
    }

    private static function createGuzzleClient(ClientMetadata $client): ClientInterface
    {
        return new GuzzleClient([
            'base_uri' => $client->baseUrl,
            'headers' => self::buildHeaders($client),
        ]);
    }

    private static function buildHeaders(ClientMetadata $client): ?array
    {
        $headers = [];
        foreach ($client->headers as $header) {
            $headers[$header->name] = $header->value;
        }

        return $headers;
    }
}