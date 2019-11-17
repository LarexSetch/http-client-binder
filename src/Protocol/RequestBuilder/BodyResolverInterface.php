<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\RequestBuilder;

use HttpClientBinder\Mapping\Dto\Endpoint;
use Psr\Http\Message\StreamInterface;

interface BodyResolverInterface
{
    /**
     * @throws CannotResolveBodyException
     */
    public function resolve(Endpoint $endpoint, array $arguments): ?StreamInterface;
}