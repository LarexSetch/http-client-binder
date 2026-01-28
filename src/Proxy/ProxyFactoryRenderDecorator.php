<?php

declare(strict_types=1);

namespace HttpClientBinder\Proxy;

final class ProxyFactoryRenderDecorator implements ProxyFactoryInterface
{
    public function __construct(
        private readonly ProxyClassNameResolverInterface $classNameResolver,
        private readonly SourceRenderInterface $sourceRender,
        private readonly SourceStorageInterface $sourceStorage,
        private readonly RenderDataFactoryInterface $renderDataFactory,
        private readonly ProxyFactoryInterface $inner
    ) {
    }

    public function build(string $interfaceName)
    {
        $source = $this->sourceRender->render($this->renderDataFactory->build($interfaceName));
        $this->sourceStorage->store($this->classNameResolver->resolve($interfaceName), $source);
        $this->sourceStorage->import($this->classNameResolver->resolve($interfaceName));

        return $this->inner->build($interfaceName);
    }
}