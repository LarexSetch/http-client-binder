<?php

declare(strict_types=1);

namespace HttpClientBinder\Annotation;

use Attribute;
use HttpClientBinder\Enums\HttpMethod;

#[Attribute(Attribute::TARGET_METHOD)]
readonly final class RequestMapping
{
    public function __construct(
        public string $uri,
        public HttpMethod $method,
        public ?string $requestType = null,
        public ?string $responseType = null,
    ) {
    }
}