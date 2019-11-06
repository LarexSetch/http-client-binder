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

    private function __construct(SerializerInterface $serializer) {
        $this->serializer = $serializer;
    }

    /**
     * @return null|StreamInterface|string
     */
    public function resolve(Endpoint $endpoint, array $arguments)
    {
        if (
            null === $endpoint->getRequestBody() ||
            !key_exists($endpoint->getRequestBody()->getArgumentName(), $arguments)
        ) {
            return null;
        }

        $body = $arguments[$endpoint->getRequestBody()->getArgumentName()];
        if (is_string($body) || $body instanceof StreamInterface) {
            return $body;
        }

        if(is_object($body)) {
            return $this->serializer->serialize($body, $endpoint->getRequestBody()->getSerializationType()->toString());
        }
        //todo exception
    }
}