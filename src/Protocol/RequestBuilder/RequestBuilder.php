<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\RequestBuilder;

use HttpClientBinder\Mapping\Dto\Endpoint;
use Psr\Http\Message\RequestInterface;

interface RequestBuilder
{
    public function build(Endpoint $endpoint, array $arguments): RequestInterface;
}