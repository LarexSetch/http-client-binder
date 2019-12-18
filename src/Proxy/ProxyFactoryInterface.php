<?php

declare(strict_types=1);

namespace HttpClientBinder\Proxy;

interface ProxyFactoryInterface
{
    /**
     * @return mixed implementation of some $interfaceName
     */
    public function build(string $interfaceName);
}