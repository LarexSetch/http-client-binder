<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\RemoteCall\RequestBuilder;

use HttpClientBinder\Codec\Type;
use HttpClientBinder\Codec\TypeBuilderInterface;
use HttpClientBinder\Codec\UnexpectedFormatException;
use HttpClientBinder\Mapping\Dto\Endpoint;
use HttpClientBinder\Mapping\Dto\HttpHeader;

final class RequestTypeBuilder implements TypeBuilderInterface
{
    public function build(Endpoint $endpoint): Type
    {
        return
            new Type(
                $this->getFormat($endpoint),
                $endpoint->getRequestType()->getType()
            );
    }

    /**
     * @throws UnexpectedFormatException
     */
    private function getFormat(Endpoint $endpoint): ?string
    {
        foreach ($endpoint->getHeaderBag()->getHeaders() as $header) {
            if ('Content-type' === $header->getName()) {
                return $this->resolveFormat($header);
            }
        }

        throw new UnexpectedFormatException('The response must have Content-type header');
    }

    /**
     * @throws UnexpectedFormatException
     */
    private function resolveFormat(HttpHeader $header): ?string
    {
        foreach ($header->getValue() as $item) {
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
    private function resolveContentType(string $contentType): ?string
    {
        return match (true) {
            'application/json' === $contentType => Type::FORMAT_JSON,
            'application/xml' === $contentType => Type::FORMAT_XML,
            default => throw new UnexpectedFormatException(sprintf('Unsupported content type %s', $contentType)),
        };
    }
}