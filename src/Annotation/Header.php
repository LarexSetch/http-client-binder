<?php

declare(strict_types=1);

namespace HttpClientBinder\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\Common\Annotations\Annotation\Required;

/**
 * @Annotation
 * @Target({"ANNOTATION"})
 */
final class Header
{
    /**
     * @var string
     * @Required
     */
    private $value;

    public function __construct(array $values)
    {
        $this->value = $values['value'];
    }

    public function getValue(): string
    {
        return $this->value;
    }
}