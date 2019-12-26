<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Dto;

use JMS\Serializer\Annotation as Serializer;

final class MappingClient
{
    /**
     * @var EndpointBag
     *
     * @Serializer\Type("HttpClientBinder\Mapping\Dto\EndpointBag")
     * @Serializer\SerializedName("endpointBag")
     */
    private $endpointBag;

    /**
     * @var HttpHeaderBag
     *
     * @Serializer\Type("HttpClientBinder\Mapping\Dto\HttpHeaderBag")
     * @Serializer\SerializedName("headerBag")
     */
    private $headerBag;

    /**
     * @var string|null
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("baseUrl")
     */
    private $baseUrl;

    public function __construct(EndpointBag $endpointBag, HttpHeaderBag $headerBag, ?string $baseUrl = null)
    {
        $this->endpointBag = $endpointBag;
        $this->headerBag = $headerBag;
        $this->baseUrl = $baseUrl;
    }

    public function getBaseUrl(): string
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