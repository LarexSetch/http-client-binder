<?php

namespace HttpClientBinder\Mapping\Extractor;

use HttpClientBinder\Mapping\Dto\HttpHeaderBag;
use ReflectionMethod;

interface HeadersExtractorInterface
{
    public function extract(ReflectionMethod $method): HttpHeaderBag;
}