<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Dto;

use JMS\Serializer\Annotation as Serializer;

final readonly class HttpHeaderBag
{
    /**
     * @param HttpHeader[] $headers
     */
    public function __construct(
        #[Serializer\Type("array<" . HttpHeader::class . ">")]
        #[Serializer\SerializedName("headers")]
        public array $headers
    ) {
    }

    /**
     * @return HttpHeader[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
}