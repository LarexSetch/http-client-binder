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
     * @var UrlParameterBag
     */
    private $parameterBag;

    /**
     * @var ResponseBody
     */
    private $responseBody;

    /**
     * @var RequestBody|null
     */
    private $requestBody;

    public function __construct(
        string $name,
        HttpMethod $method,
        Url $url,
        UrlParameterBag $parameterBag,
        HttpHeaderBag $headerBag,
        ResponseBody $responseBody,
        ?RequestBody $requestBody
    ) {
        $this->name = $name;
        $this->method = $method;
        $this->url = $url;
        $this->parameterBag = $parameterBag;
        $this->headerBag = $headerBag;
        $this->responseBody = $responseBody;
        $this->requestBody = $requestBody;
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

    public function getParameterBag(): UrlParameterBag
    {
        return $this->parameterBag;
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