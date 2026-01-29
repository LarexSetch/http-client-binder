<?php

declare(strict_types=1);

namespace HttpClientBinder\Proxy\ResultFactory;

use Exception;
use HttpClientBinder\Codec\DecoderInterface;
use HttpClientBinder\Codec\Type;
use HttpClientBinder\Metadata\Dto\Endpoint;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

readonly class BaseResultFactory implements ResultFactory
{
    public function __construct(
        private DecoderInterface $decoder,

    ) {
    }

    /**
     * @throws Exception
     */
    public function create(Endpoint $endpoint, ResponseInterface $response): mixed
    {
        if ($endpoint->resultType === StreamInterface::class) {
            return $response->getBody();
        }

        return $this->decoder->decode($response->getBody(), $endpoint->resultType, $this->resolveType($response));
    }

    /**
     * @throws Exception
     */
    private function resolveType(ResponseInterface $response): Type
    {
        foreach ($response->getHeaders() as $name => $header) {
            if ('content-type' === strtolower($name)) {
                return $this->resolveFormat($header);
            }
        }

        throw new Exception('The response must have Content-type header');
    }

    /**
     * @throws Exception
     */
    private function resolveFormat(array $values): Type
    {
        foreach ($values as $contentType) {
            return match (true) {
                'application/json' === $contentType => Type::JSON,
                'application/xml' === $contentType => Type::XML,
                preg_match('/text\//', $contentType) => Type::TEXT,
                default => throw new Exception(sprintf('Unsupported content type %s', $contentType)),
            };
        }

        throw new Exception('Cannot resolve format');
    }
}
