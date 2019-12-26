<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Dto;

use JMS\Serializer\Annotation as Serializer;

final class MappingClient
{
    /**
     * @var string|null
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("baseUrl")
     */
    private $baseUrl;

    /**
     * @var EndpointBag
     *
     * @Serializer\Type("HttpClientBinder\Mapping\Dto\EndpointBag")
     * @Serializer\SerializedName("endpointBag")
     */
    private $endpointBag;

    public function __construct(EndpointBag $endpointBag, ?string $baseUrl = null)
    {
        $this->baseUrl = $baseUrl;
        $this->endpointBag = $endpointBag;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function getEndpointBag(): EndpointBag
    {
        return $this->endpointBag;
    }
}