<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\RemoteCall\ResponseDecoder;

use HttpClientBinder\Codec\Type;
use HttpClientBinder\Codec\TypeBuilderInterface;
use HttpClientBinder\Codec\UnexpectedFormatException;
use HttpClientBinder\Mapping\Dto\Endpoint;
use Psr\Http\Message\ResponseInterface;

final readonly class ResponseTypeBuilder implements TypeBuilderInterface
{
    public function __construct(
        private readonly ResponseInterface $response
    ) {
    }

    public function build(Endpoint $endpoint): Type
    {
        return
            new Type(
                $this->getFormat(),
                $endpoint->getResponseType()
            );
    }

    public static function create(ResponseInterface $response): TypeBuilderInterface
    {
        return new ResponseTypeBuilder($response);
    }

    /**
     * @throws UnexpectedFormatException
     */
    private function getFormat(): string
    {
        foreach ($this->response->getHeaders() as $name => $header) {
            if ('content-type' === strtolower($name)) {
                return $this->resolveFormat($header);
            }
        }

        throw new UnexpectedFormatException('The response must have Content-type header');
    }

    /**
     * @throws UnexpectedFormatException
     */
    private function resolveFormat(array $values): string
    {
        foreach ($values as $item) {
            return $this->resolveContentType($item);
        }

        throw new UnexpectedFormatException('Cannot resolve format');
    }

    /**
     * @throws UnexpectedFormatException
     */
    private function resolveContentType(string $contentType): string
    {
        return match (true) {
            'application/json' === $contentType => Type::FORMAT_JSON,
            'application/xml' === $contentType => Type::FORMAT_XML,
            preg_match('/text\//', $contentType) => Type::FORMAT_TEXT,
            default => throw new UnexpectedFormatException(sprintf('Unsupported content type %s', $contentType)),
        };
    }
}