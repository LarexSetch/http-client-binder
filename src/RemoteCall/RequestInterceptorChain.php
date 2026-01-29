<?php

declare(strict_types=1);

namespace HttpClientBinder\RemoteCall;

use Psr\Http\Message\RequestInterface;

final readonly class RequestInterceptorChain implements RequestInterceptorInterface
{
    public function __construct(
        /** @var RequestInterceptorInterface[] */
        private readonly array $chain
    ) {
    }

    public function intercept(RequestInterface $request): RequestInterface
    {
        foreach ($this->chain as $interceptor) {
            $request = $interceptor->intercept($request);
        }

        return $request;
    }

    public static function create(?array $chain = []): RequestInterceptorInterface
    {
        return new static($chain);
    }
}