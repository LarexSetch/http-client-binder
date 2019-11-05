<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Dto;

final class HttpHeaderBag
{
    /**
     * @var HttpHeader[]
     */
    private $headers;

    /**
     * HttpHeaderBag constructor.
     * @param HttpHeader[] $headers
     */
    public function __construct(array $headers)
    {
        $this->headers = $headers;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
}