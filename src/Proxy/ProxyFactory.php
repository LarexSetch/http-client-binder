<?php

declare(strict_types=1);

namespace HttpClientBinder\Proxy;

use HttpClientBinder\Protocol\MagicProtocolFactoryInterface;

final class ProxyFactory implements ProxyFactoryInterface
{
    public function __construct(
        private readonly ProxyClassNameResolverInterface $classNameResolver,
        private readonly MagicProtocolFactoryInterface $magicProtocolFactory
    ) {
    }

    public function build(string $interfaceName)
    {
        $className = $this->classNameResolver->resolve($interfaceName);

        return new $className($this->magicProtocolFactory);
    }
}