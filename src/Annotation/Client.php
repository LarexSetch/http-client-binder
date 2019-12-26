<?php

declare(strict_types=1);

namespace HttpClientBinder\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
final class Client
{
    /**
     * @var string|null
     */
    private $baseUrl;

    /**
     * @var Header[]
     */
    private $headers = [];

    public function __construct(array $value)
    {
        if(isset($value['value'])) {
            $this->baseUrl = $value['value'];
        } elseif(isset($value['baseUrl'])) {
            $this->baseUrl = $value['baseUrl'];
        }

        if(isset($value['headers'])) {
            $this->headers = $value['headers'];
        }
    }

    public function getBaseUrl(): ?string
    {
        return $this->baseUrl;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
}