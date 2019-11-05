<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Dto;

final class EndpointBag
{
    /**
     * @var Endpoint[]
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

    public function getEndpoints(): array
    {
        return $this->endpoints;
    }
}