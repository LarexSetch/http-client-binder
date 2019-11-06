<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\RequestBuilder\BodyResolver;

use HttpClientBinder\Mapping\Dto\Endpoint;
use Psr\Http\Message\StreamInterface;

interface BodyResolver
{
    /**
     * @return null|StreamInterface|string
     */
    public function resolve(Endpoint $endpoint, array $arguments);
}