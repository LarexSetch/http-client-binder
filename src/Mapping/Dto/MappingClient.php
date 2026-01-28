<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Dto;

use JMS\Serializer\Annotation as Serializer;

final readonly class MappingClient
{
    public function __construct(
        #[Serializer\Type(EndpointBag::class)]
        #[Serializer\SerializedName("endpointBag")]
        public EndpointBag $endpointBag,

        #[Serializer\Type(HttpHeaderBag::class)]
        #[Serializer\SerializedName("headerBag")]
        public HttpHeaderBag $headerBag,

        #[Serializer\Type("string")]
        #[Serializer\SerializedName("baseUrl")]
        public ?string $baseUrl = null
    ) {
    }

    public function getBaseUrl(): ?string
    {
        return $this->baseUrl;
    }

    public function getEndpointBag(): EndpointBag
    {
        return $this->endpointBag;
    }

    public function getHeaderBag(): HttpHeaderBag
    {
        return $this->headerBag;
    }
}