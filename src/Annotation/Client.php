<?php

declare(strict_types=1);

namespace HttpClientBinder\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\Common\Annotations\Annotation\Required;
use InvalidArgumentException;

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
        if(isset($value['value'])) {
            $this->baseUrl = $value['value'];
        } elseif(isset($value['baseUrl'])) {
            $this->baseUrl = $value['baseUrl'];
        } else {
            throw new InvalidArgumentException('You must set @Client("http://example.com") or @Client(baseUrl="http://example.com")');
        }
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }
}