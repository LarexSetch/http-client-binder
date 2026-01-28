<?php

declare(strict_types=1);

namespace HttpClientBinder\Annotation;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final readonly class ParameterBag
{
    /**
     * @param Parameter[] $parameters
     */
    public function __construct(public array $parameters)
    {
    }
}