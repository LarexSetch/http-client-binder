<?php

declare(strict_types=1);

namespace HttpClientBinder\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\Common\Annotations\Annotation\Required;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
final class RequestMapping
{
    /**
     * @var string
     * @Required()
     */
    private $uri;

    /**
     * @var string
     * @Required
     */
    private $method;

    /**
     * @var string
     */
    private $requestType;

    /**
     * @var string
     */
    private $responseType;

    public function __construct(array $values)
    {
        $this->uri = $values['value'];
        $this->method = $values['method'];
        $this->requestType = $values["requestType"];
        $this->responseType = $values["responseType"];
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getRequestType(): string
    {
        return $this->requestType;
    }

    public function getResponseType(): string
    {
        return $this->responseType;
    }
}