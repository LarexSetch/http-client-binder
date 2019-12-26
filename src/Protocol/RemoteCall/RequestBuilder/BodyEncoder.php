<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\RemoteCall\RequestBuilder;

use HttpClientBinder\Codec\EncoderInterface;
use HttpClientBinder\Codec\Type;
use JMS\Serializer\SerializerInterface;
use Psr\Http\Message\StreamInterface;

final class BodyEncoder implements EncoderInterface
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var StreamBuilderInterface
     */
    private $streamBuilder;

    public function __construct(SerializerInterface $serializer, StreamBuilderInterface $streamBuilder)
    {
        $this->serializer = $serializer;
        $this->streamBuilder = $streamBuilder;
    }

    /**
     * @param mixed $object
     */
    public function encode($object, Type $type): StreamInterface
    {
        return
            $this->streamBuilder->build(
                $this->serializer->serialize(
                    $object,
                    $type->getFormat(),
                    null,
                    $type->getClassName()
                )
            );
    }
}