<?php

declare(strict_types=1);

namespace HttpClientBinder\Proxy;

interface ProxyClassNameResolverInterface
{
    public function resolve(string $interfaceName): string;
}