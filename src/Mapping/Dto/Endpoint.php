<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Dto;

use HttpClientBinder\Mapping\Enum\HttpMethod;

final class Endpoint
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var HttpMethod
     */
    private $method;

    /**
     * @var Url
     */
    private $url;

    /**
     * @var HttpHeaderBag
     */
    private $headerBag;

    /**
     * @var string
     */
    private $responseType;

    /**
     * @var string|null
     */
    private $requestType;

    public function __construct(
        string $name,
        HttpMethod $method,
        Url $url,
        HttpHeaderBag $headerBag,
        string $responseType,
        ?string $requestType
    ) {
        $this->name = $name;
        $this->method = $method;
        $this->url = $url;
        $this->headerBag = $headerBag;
        $this->responseType = $responseType;
        $this->requestType = $requestType;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMethod(): HttpMethod
    {
        return $this->method;
    }

    public function getUrl(): Url
    {
        return $this->url;
    }

    public function getHeaderBag(): HttpHeaderBag
    {
        return $this->headerBag;
    }

    public function getResponseType(): string
    {
        return $this->responseType;
    }

    public function getRequestType(): ?string
    {
        return $this->requestType;
    }
}