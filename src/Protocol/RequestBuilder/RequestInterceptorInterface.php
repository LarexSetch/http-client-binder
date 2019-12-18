<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol;

use HttpClientBinder\Protocol\RequestBuilder\Dto\RequestTemplate;

interface RequestInterceptorInterface
{
    public function intercept(RequestTemplate $request);
}