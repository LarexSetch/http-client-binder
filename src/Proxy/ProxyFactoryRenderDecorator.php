<?php

declare(strict_types=1);

namespace HttpClientBinder\Proxy;

use HttpClientBinder\Method\MethodsProviderFactoryInterface;
use HttpClientBinder\Protocol\MagicProtocol;
use HttpClientBinder\Proxy\Dto\RenderData;
use JMS\Serializer\SerializerInterface;

final class ProxyFactoryRenderDecorator implements ProxyFactoryInterface
{
    /**
     * @var ProxyClassNameResolverInterface
     */
    private $classNameResolver;

    /**
     * @var ProxyFactoryInterface
     */
    private $inner;

    /**
     * @var SourceRenderInterface
     */
    private $sourceRender;

    /**
     * @var SourceStorageInterface
     */
    private $sourceStorage;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var MethodsProviderFactoryInterface
     */
    private $methodsProviderFactory;

    /**
     * @var RenderDataFactoryInterface
     */
    private $renderDataFactory;

    public function __construct(
        ProxyClassNameResolverInterface $classNameResolver,
        SourceRenderInterface $sourceRender,
        SourceStorageInterface $sourceStorage,
        RenderDataFactoryInterface $renderDataFactory,
        ProxyFactoryInterface $inner
    ) {
        $this->classNameResolver = $classNameResolver;
        $this->inner = $inner;
        $this->sourceRender = $sourceRender;
        $this->sourceStorage = $sourceStorage;
        $this->renderDataFactory = $renderDataFactory;
    }

    public function build(string $interfaceName)
    {
        $source = $this->sourceRender->render($this->renderDataFactory->build($interfaceName));
        $this->sourceStorage->store($this->classNameResolver->resolve($interfaceName), $source);
        $this->sourceStorage->import($this->classNameResolver->resolve($interfaceName));

        return $this->inner->build($interfaceName);
    }
}