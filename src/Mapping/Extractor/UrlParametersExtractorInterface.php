<?php

namespace HttpClientBinder\Mapping\Extractor;

use HttpClientBinder\Mapping\Dto\UrlParameterBag;
use ReflectionMethod;

interface UrlParametersExtractorInterface
{
    public function extract(ReflectionMethod $method): UrlParameterBag;
}