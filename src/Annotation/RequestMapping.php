<?php

declare(strict_types=1);

namespace HttpClientBinder\Annotation;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
readonly final class RequestMapping
{
    public function __construct(
        public string $uri,
        public string $method,
        public ?string $requestType = null,
        public ?string $responseType = null,
    ) {
    }
}