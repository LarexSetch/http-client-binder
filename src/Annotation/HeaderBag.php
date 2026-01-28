<?php

declare(strict_types=1);

namespace HttpClientBinder\Annotation;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final readonly class HeaderBag
{
    /**
     * @param Header[] $headers
     */
    public function __construct(public array $headers)
    {
    }
}