<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\RemoteCall;

use HttpClientBinder\Mapping\Dto\Endpoint;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

interface RemoteCallInterface
{
    /**
     * @return mixed|ResponseInterface|StreamInterface
     */
    public function invoke(Endpoint $endpoint, array $arguments);
}