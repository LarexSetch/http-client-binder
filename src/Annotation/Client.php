<?php

declare(strict_types=1);

namespace HttpClientBinder\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\Common\Annotations\Annotation\Required;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
final class Client
{
    /**
     * @var string
     * @Required
     */
    private $baseUrl;

    public function __construct(array $value)
    {
        $this->baseUrl = $value['baseUrl'];
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }
}