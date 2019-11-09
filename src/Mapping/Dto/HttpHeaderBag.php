<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Dto;

use JMS\Serializer\Annotation as Serializer;

final class HttpHeaderBag
{
    /**
     * @var HttpHeader[]
     *
     * @Serializer\Type("array<HttpClientBinder\Mapping\Dto\HttpHeader>")
     * @Serializer\SerializedName("headers")
     */
    private $headers;

    public function __construct(array $headers)
    {
        $this->headers = $headers;
    }

    /**
     * @return HttpHeader[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
}