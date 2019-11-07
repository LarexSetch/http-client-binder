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
    private $header;

    /**
     * @var string[]
     * @Required
     */
    private $values;

    public function __construct(array $values)
    {
        $this->header = $values['value'];
        if(is_array($values['values'])) {
            $this->values = $values['values'];
        } else {
            $this->values = [$values['values']];
        }
    }

    public function getHeader(): string
    {
        return $this->header;
    }

    public function getValues(): array
    {
        return $this->values;
    }
}