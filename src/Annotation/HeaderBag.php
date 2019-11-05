<?php

declare(strict_types=1);

namespace HttpClientBinder\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
final class HeaderBag
{
    /**
     * @var Header[]
     * @Required
     */
    private $headers;

    public function __construct(array $values)
    {
        $this->headers = $values["value"];
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
}