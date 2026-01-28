<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\RemoteCall\RequestBuilder;

use HttpClientBinder\Codec\EncoderInterface;
use HttpClientBinder\Codec\Type;
use JMS\Serializer\SerializerInterface;
use Psr\Http\Message\StreamInterface;

final readonly class BodyEncoder implements EncoderInterface
{
    public function __construct(
        private SerializerInterface $serializer,
        private StreamBuilderInterface $streamBuilder
    ) {
    }

    public function encode(mixed $object, Type $type): StreamInterface
    {
        return
            $this->streamBuilder->build(
                $this->serializer->serialize(
                    $object,
                    $type->format,
                    null,
                    $type->className
                )
            );
    }
}