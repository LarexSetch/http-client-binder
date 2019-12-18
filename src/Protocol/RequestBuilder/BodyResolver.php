<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\RequestBuilder;

use HttpClientBinder\Codec\EncoderInterface;
use HttpClientBinder\Codec\TypeBuilderInterface;
use HttpClientBinder\Mapping\Dto\Endpoint;
use JMS\Serializer\SerializerInterface;
use Psr\Http\Message\StreamInterface;

final class BodyResolver implements BodyResolverInterface
{

    /**
     * @var StreamBuilderInterface
     */
    private $streamBuilder;

    /**
     * @var EncoderInterface
     */
    private $encoder;

    /**
     * @var TypeBuilderInterface
     */
    private $typeBuilder;

    public function __construct(
        StreamBuilderInterface $streamBuilder,
        EncoderInterface $encoder,
        TypeBuilderInterface $typeBuilderFactory
    ) {
        $this->streamBuilder = $streamBuilder;
        $this->encoder = $encoder;
        $this->typeBuilder = $typeBuilderFactory;
    }

    public function resolve(Endpoint $endpoint, array $arguments): ?StreamInterface
    {
        if (
            null === $endpoint->getRequestType() ||
            !key_exists($endpoint->getRequestType()->getArgument(), $arguments)
        ) {
            return null;
        }

        $body = $arguments[$endpoint->getRequestType()->getArgument()];
        if (is_string($body)) {
            return $this->streamBuilder->build($body);
        }

        if ($body instanceof StreamInterface) {
            return $body;
        }

        if (is_object($body)) {
            return $this->encoder->encode($body, $this->typeBuilder->build($endpoint));
        }

        throw new CannotResolveBodyException(sprintf('Unsupported type %s', gettype($body)));
    }
}