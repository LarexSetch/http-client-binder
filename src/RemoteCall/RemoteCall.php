<?php

declare(strict_types=1);

namespace HttpClientBinder\RemoteCall;

use Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface RemoteCall
{
    /**
     * @throws Exception // TODO set up custom exception
     */
    public function invoke(RequestInterface $request): ResponseInterface;
}