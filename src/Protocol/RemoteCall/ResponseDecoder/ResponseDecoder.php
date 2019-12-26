<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\RemoteCall\ResponseDecoder;

use HttpClientBinder\Codec\DecoderInterface;
use HttpClientBinder\Codec\Type;
use JMS\Serializer\SerializerInterface;
use Psr\Http\Message\StreamInterface;

final class ResponseDecoder implements DecoderInterface
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @return mixed
     */
    public function decode(StreamInterface $stream, Type $type)
    {
        if (StreamInterface::class === $type->getClassName()) {
            return $stream;
        }

        return
            $this->serializer->deserialize(
                $stream->getContents(),
                $type->getClassName(),
                $type->getFormat()
            );
    }
}