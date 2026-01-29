<?php

declare(strict_types=1);

namespace HttpClientBinder\Annotation;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
readonly class PathParameter
{
    public function __construct(public string $name)
    {
    }
}
