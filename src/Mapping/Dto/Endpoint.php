<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Dto;

use JMS\Serializer\Annotation as Serializer;

final readonly class Endpoint
{
    public function __construct(
        #[Serializer\Type("string")]
        #[Serializer\SerializedName("name")]
        public string $name,

        #[Serializer\Type("string")]
        #[Serializer\SerializedName("method")]
        public string $method,

        #[Serializer\Type("string")]
        #[Serializer\SerializedName("responseType")]
        public string $responseType,

        #[Serializer\Type(Url::class)]
        #[Serializer\SerializedName("url")]
        public Url $url,

        #[Serializer\Type(HttpHeaderBag::class)]
        #[Serializer\SerializedName("headerBag")]
        public HttpHeaderBag $headerBag,

        #[Serializer\Type(RequestType::class)]
        #[Serializer\SerializedName("requestType")]
        public ?RequestType $requestType = null
    ) {
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