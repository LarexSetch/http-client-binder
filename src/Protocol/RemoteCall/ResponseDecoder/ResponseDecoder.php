<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\RemoteCall\ResponseDecoder;

use HttpClientBinder\Codec\DecoderInterface;
use HttpClientBinder\Codec\Type;
use JMS\Serializer\SerializerInterface;
use Psr\Http\Message\StreamInterface;

final readonly class ResponseDecoder implements DecoderInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer
    ) {
    }

    public function decode(StreamInterface $stream, Type $type): mixed
    {
        if (StreamInterface::class === $type->className) {
            return $stream;
        }

        return
            $this->serializer->deserialize(
                $stream->getContents(),
                $type->className,
                $type->format
            );
    }
}