<?php

namespace HttpClientBinder\Mapping\Extractor;

use HttpClientBinder\Mapping\Dto\RequestType;
use ReflectionMethod;

interface RequestTypeExtractorInterface
{
    public function extract(ReflectionMethod $method): ?RequestType;
}