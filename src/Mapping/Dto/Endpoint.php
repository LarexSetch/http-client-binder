<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Dto;

use HttpClientBinder\Mapping\Enum\HttpMethod;
use JMS\Serializer\Annotation as Serializer;

final class Endpoint
{
    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("name")
     */
    private $name;

    /**
     * @var HttpMethod
     *
     * @Serializer\Type("HttpClientBinder\Mapping\Dto\HttpMethod")
     * @Serializer\SerializedName("method")
     */
    private $method;

    /**
     * @var Url
     *
     * @Serializer\Type("HttpClientBinder\Mapping\Dto\Url")
     * @Serializer\SerializedName("url")
     */
    private $url;

    /**
     * @var HttpHeaderBag
     *
     * @Serializer\Type("HttpClientBinder\Mapping\Dto\HttpHeaderBag")
     * @Serializer\SerializedName("headerBag")
     */
    private $headerBag;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("responseType")
     */
    private $responseType;

    /**
     * @var string|null
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("responseType")
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