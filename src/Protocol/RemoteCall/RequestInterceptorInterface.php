<?php

namespace HttpClientBinder\Protocol\RemoteCall;

use Psr\Http\Message\RequestInterface;

interface RequestInterceptorInterface
{
    public function intercept(RequestInterface $request): RequestInterface;
}