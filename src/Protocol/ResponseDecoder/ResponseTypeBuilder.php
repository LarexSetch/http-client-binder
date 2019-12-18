<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\ResponseDecoder;

use HttpClientBinder\Codec\Type;
use HttpClientBinder\Codec\TypeBuilderInterface;
use HttpClientBinder\Codec\UnexpectedFormatException;
use HttpClientBinder\Mapping\Dto\Endpoint;
use Psr\Http\Message\ResponseInterface;

final class ResponseTypeBuilder implements TypeBuilderInterface
{
    /**
     * @var ResponseInterface
     */
    private $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
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
            $format = $this->resolveContentType($item);
            if (null !== $format) {
                return $format;
            }
        }

        throw new UnexpectedFormatException('Cannot resolve format');
    }

    /**
     * @throws UnexpectedFormatException
     */
    private function resolveContentType(string $contentType): string
    {
        switch (true) {
            case 'application/json' === $contentType:
                return Type::FORMAT_JSON;
            case 'application/xml'=== $contentType:
                return Type::FORMAT_XML;
            case preg_match('/text\//', $contentType):
                return Type::FORMAT_TEXT;
            default:
                throw new UnexpectedFormatException(sprintf('Unsupported content type %s', $contentType));
        }
    }
}