<?php

declare(strict_types=1);

namespace HttpClientBinder\Proxy\RequestFactory;

use HttpClientBinder\Metadata\Dto\Endpoint;
use Psr\Http\Message\RequestInterface;

interface RequestFactory
{
    public function create(Endpoint $endpoint, array $arguments): RequestInterface;
}
