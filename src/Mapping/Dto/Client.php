<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Dto;

final class Client
{
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var EndpointBag
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