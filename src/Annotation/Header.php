<?php

declare(strict_types=1);

namespace HttpClientBinder\Annotation;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
readonly final class Header
{
    public function __construct(public string $header, private array|string $values)
    {
    }

    public function getValues(): array
    {
        return is_string($this->values) ? [$this->values] : $this->values;
    }
}