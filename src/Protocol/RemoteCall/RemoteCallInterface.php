<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\RemoteCall;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface RemoteCallInterface
{
    public function invoke(RequestInterface $request): ResponseInterface;
}