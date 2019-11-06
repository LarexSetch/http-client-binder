<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\RequestBuilder\UrlResolver;

use HttpClientBinder\Mapping\Dto\Endpoint;

interface UrlResolver
{
    public function resolve(Endpoint $endpoint, array $arguments): string;
}