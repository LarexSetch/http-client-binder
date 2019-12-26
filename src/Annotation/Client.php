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

    public function __construct(array $value)
    {
        if(isset($value['value'])) {
            $this->baseUrl = $value['value'];
        } elseif(isset($value['baseUrl'])) {
            $this->baseUrl = $value['baseUrl'];
        }
    }

    public function getBaseUrl(): ?string
    {
        return $this->baseUrl;
    }
}