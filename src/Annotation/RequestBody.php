<?php

declare(strict_types=1);

namespace HttpClientBinder\Annotation;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class RequestBody
{
    public function __construct(string $argumentName)
    {
    }
}