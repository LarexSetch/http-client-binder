<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\RequestBuilder\BodyResolver;

use HttpClientBinder\Mapping\Dto\Endpoint;
use JMS\Serializer\SerializerInterface;
use Psr\Http\Message\StreamInterface;

final class BodyResolverStrategy implements BodyResolver
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function resolve(Endpoint $endpoint, array $arguments)
    {
        if (
            null === $endpoint->getRequestType() ||
            !key_exists($endpoint->getRequestBody()->getArgumentName(), $arguments)
        ) {
            return null;
        }

        $body = $arguments[$endpoint->getRequestBody()->getArgumentName()];
        if (is_string($body) || $body instanceof StreamInterface) {
            return $body;
        }

        if (is_object($body)) {
            return $this->serializer->serialize($body, $endpoint->getRequestBody()->getSerializationType()->toString());
        }

        throw new CannotResolveBodyException(sprintf('Unsupported type %s', gettype($body)));
    }
}