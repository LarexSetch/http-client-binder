<?php

declare(strict_types=1);

namespace HttpClientBinder\Proxy;

use HttpClientBinder\Fabrics\Protocol\MagicProtocolFactoryInterface;

final class ProxyFactory implements ProxyFactoryInterface
{
    /**
     * @var ProxyClassNameResolverInterface
     */
    private $classNameResolver;

    /**
     * @var MagicProtocolFactoryInterface
     */
    private $magicProtocolFactory;

    public function __construct(
        ProxyClassNameResolverInterface $classNameResolver,
        MagicProtocolFactoryInterface $magicProtocolFactory
    ) {
        $this->classNameResolver = $classNameResolver;
        $this->magicProtocolFactory = $magicProtocolFactory;
    }

    public function build(string $interfaceName)
    {
        $className = $this->classNameResolver->resolve($interfaceName);

        return new $className($this->magicProtocolFactory);
    }
}