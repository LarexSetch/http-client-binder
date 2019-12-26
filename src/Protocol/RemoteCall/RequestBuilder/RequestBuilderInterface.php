<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\RemoteCall\RequestBuilder;

use HttpClientBinder\Mapping\Dto\Endpoint;
use Psr\Http\Message\RequestInterface;

interface RequestBuilderInterface
{
    public function build(Endpoint $endpoint, array $arguments): RequestInterface;
}