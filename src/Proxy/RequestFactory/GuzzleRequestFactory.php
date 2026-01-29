<?php

declare(strict_types=1);

namespace HttpClientBinder\Proxy\RequestFactory;

use GuzzleHttp\Psr7\Request;
use HttpClientBinder\Codec\EncoderInterface;
use HttpClientBinder\Codec\Type;
use HttpClientBinder\Metadata\Dto\Endpoint;
use HttpClientBinder\Metadata\Dto\EndpointArgument;
use HttpClientBinder\Utils\StringToStream;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;

final readonly class GuzzleRequestFactory implements RequestFactory
{
    public function __construct(
        private EncoderInterface $encoder,
    ) {
    }

    /**
     * @throws CannotResolveBodyException
     */
    public function create(Endpoint $endpoint, array $arguments): RequestInterface
    {
        return new Request(
            $endpoint->route->method->value,
            UrlBuilder::build($endpoint->route, $arguments),
            self::assembleHeaders($endpoint, $arguments),
            $this->createBody($endpoint, $arguments)
        );
    }

    private static function assembleHeaders(Endpoint $endpoint, array $arguments): array
    {
        $headers = [];
        foreach ($endpoint->headers as $header) {
            $headers[$header->name] = strtr(
                $header->value,
                array_map(
                    fn(EndpointArgument $argument) => $arguments[$argument->position],
                    $header->parameters
                )
            );
        }

        return $headers;
    }

    /**
     * @throws CannotResolveBodyException
     */
    private function createBody(Endpoint $endpoint, array $arguments): ?StreamInterface
    {
        if (
            null === $endpoint->requestBody ||
            !key_exists($endpoint->requestBody->position, $arguments)
        ) {
            return null;
        }

        $body = $arguments[$endpoint->requestBody->position];
        if (is_string($body) || is_numeric($body) || is_bool($body)) {
            return StringToStream::create($body);
        }

        if ($body instanceof StreamInterface) {
            return $body;
        }

        if (is_object($body)) {
            return $this->encoder->encode($body, self::resolveType($endpoint));
        }

        throw new CannotResolveBodyException(sprintf('Unsupported type %s', gettype($body)));
    }

    private static function resolveType(Endpoint $endpoint): Type
    {
        foreach ($endpoint->headers as $header) {
            if (strtolower($header->name) === strtolower('Content-Type')) {
                return match (true) {
                    str_contains($header->value, 'json') => Type::JSON,
                    str_contains($header->value, 'xml') => Type::XML,
                    default => Type::TEXT,
                };
            }
        }

        return Type::TEXT;
    }
}