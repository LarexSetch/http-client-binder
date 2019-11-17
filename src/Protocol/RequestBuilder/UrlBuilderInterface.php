<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\RequestBuilder;

use HttpClientBinder\Mapping\Dto\Endpoint;

interface UrlBuilderInterface
{
    public function build(Endpoint $endpoint, array $arguments): string;
}