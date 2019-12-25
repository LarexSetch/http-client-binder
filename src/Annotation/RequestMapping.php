<?php

declare(strict_types=1);

namespace HttpClientBinder\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\Common\Annotations\Annotation\Required;
use InvalidArgumentException;

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
     * @Required()
     */
    private $method;

    /**
     * @var string|null
     */
    private $requestType;

    /**
     * @var string|null
     */
    private $responseType;

    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->uri = $values['value'];
        } elseif (isset($values['uri'])) {
            $this->uri = $values['uri'];
        }

        if (isset($values['method'])) {
            $this->method = $values['method'];
        }

        if (isset($values['requestType'])) {
            $this->requestType = $values['requestType'];
        }

        if (isset($values['responseType'])) {
            $this->responseType = $values['responseType'];
        }

        $this->checkRequestMapping();
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getRequestType(): ?string
    {
        return $this->requestType;
    }

    public function getResponseType(): ?string
    {
        return $this->responseType;
    }

    private function checkRequestMapping(): void
    {
        if (null === $this->uri || null === $this->method) {
            throw $this->createRequiredException();
        }
    }

    private function createRequiredException(): InvalidArgumentException
    {
        return
            new InvalidArgumentException(
                'You mus define request mapping as ' .
                '@RequestMapping("/path/to/api", method="POST") or ' .
                '@RequestMapping(uri="/path/to/api", method="POST") or ' .
                '@RequestMapping(uri="/path/to/api/{id}", method="GET")'
            );
    }
}