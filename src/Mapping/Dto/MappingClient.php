<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Dto;

use JMS\Serializer\Annotation as Serializer;

final class MappingClient
{
    /**
     * @var string
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

    /**
     * Client constructor.
     * @param string $baseUrl
     * @param EndpointBag $endpointBag
     */
    public function __construct(string $baseUrl, EndpointBag $endpointBag)
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