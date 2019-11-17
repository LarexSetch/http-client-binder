<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Dto;

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
     * @var string
     *
     * @Serializer\Type("string")
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
     * @var RequestType|null
     *
     * @Serializer\Type("HttpClientBinder\Mapping\Dto\RequestType")
     * @Serializer\SerializedName("requestType")
     */
    private $requestType;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("responseType")
     */
    private $responseType;

    public function __construct(
        string $name,
        string $method,
        Url $url,
        HttpHeaderBag $headerBag,
        ?RequestType $requestType,
        string $responseType
    ) {
        $this->name = $name;
        $this->method = $method;
        $this->url = $url;
        $this->headerBag = $headerBag;
        $this->requestType = $requestType;
        $this->responseType = $responseType;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMethod(): string
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

    public function getRequestType(): ?RequestType
    {
        return $this->requestType;
    }

    public function getResponseType(): string
    {
        return $this->responseType;
    }
}