<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Dto;

use HttpClientBinder\Mapping\Enum\HttpMethod;

final class Endpoint
{
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
     * @var ResponseBody
     */
    private $responseBody;

    /**
     * @var RequestBody|null
     */
    private $requestBody;

    /**
     * Endpoint constructor.
     * @param HttpMethod $method
     * @param Url $url
     * @param HttpHeaderBag $headerBag
     * @param ResponseBody $responseBody
     * @param RequestBody $requestBody
     */
    public function __construct(
        HttpMethod $method,
        Url $url,
        HttpHeaderBag $headerBag,
        ResponseBody $responseBody,
        ?RequestBody $requestBody
    ) {
        $this->method = $method;
        $this->url = $url;
        $this->headerBag = $headerBag;
        $this->responseBody = $responseBody;
        $this->requestBody = $requestBody;
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

    public function getResponseBody(): ResponseBody
    {
        return $this->responseBody;
    }

    public function getRequestBody(): ?RequestBody
    {
        return $this->requestBody;
    }
}