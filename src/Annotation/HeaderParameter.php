<?php

declare(strict_types=1);

namespace HttpClientBinder\Annotation;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
readonly class HeaderParameter
{
    public function __construct(public string $name, public ?string $pattern = null)
    {
    }
}
