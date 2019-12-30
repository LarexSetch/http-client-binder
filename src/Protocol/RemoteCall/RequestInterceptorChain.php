<?php

namespace HttpClientBinder\Protocol\RemoteCall;

use Psr\Http\Message\RequestInterface;

final class RequestInterceptorChain implements RequestInterceptorInterface
{
    /**
     * @var RequestInterceptorInterface[]
     */
    private $chain;

    public function __construct(array $chain)
    {
        $this->chain = $chain;
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