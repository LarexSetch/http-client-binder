<?php

declare(strict_types=1);

namespace HttpClientBinder\RemoteCall;

use Psr\Http\Message\RequestInterface;

interface RequestInterceptorInterface
{
    public function intercept(RequestInterface $request): RequestInterface;
}