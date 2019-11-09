<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Dto;

use JMS\Serializer\Annotation as Serializer;

final class EndpointBag
{
    /**
     * @var Endpoint[]
     *
     * @Serializer\Type("array<HttpClientBinder\Mapping\Dto\Endpoint>")
     * @Serializer\SerializedName("endpoints")
     */
    private $endpoints;

    /**
     * EndpointBag constructor.
     * @param Endpoint[] $endpoints
     */
    public function __construct(array $endpoints)
    {
        $this->endpoints = $endpoints;
    }

    /**
     * @return Endpoint[]
     */
    public function getEndpoints(): array
    {
        return $this->endpoints;
    }
}