<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\RemoteCall;

use HttpClientBinder\Metadata\Dto\Endpoint;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

interface RemoteCall
{
    /**
     * @throws \Exception // TODO set up custom exception
     */
    public function invoke(RequestInterface $request): ResponseInterface;
}