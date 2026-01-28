<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\RemoteCall\RequestBuilder;

use HttpClientBinder\Codec\EncoderInterface;
use HttpClientBinder\Codec\TypeBuilderInterface;
use HttpClientBinder\Codec\UnexpectedFormatException;
use HttpClientBinder\Mapping\Dto\Endpoint;
use Psr\Http\Message\StreamInterface;

final readonly class BodyResolver implements BodyResolverInterface
{
    public function __construct(
        private readonly StreamBuilderInterface $streamBuilder,
        private readonly EncoderInterface $encoder,
        private readonly TypeBuilderInterface $typeBuilder
    ) {
    }

    /**
     * @throws UnexpectedFormatException
     * @throws CannotResolveBodyException
     */
    public function resolve(Endpoint $endpoint, array $arguments): ?StreamInterface
    {
        if (
            null === $endpoint->getRequestType() ||
            !key_exists($endpoint->getRequestType()->getIndex(), $arguments)
        ) {
            return null;
        }

        $body = $arguments[$endpoint->getRequestType()->getIndex()];
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