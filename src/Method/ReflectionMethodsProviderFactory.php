<?php

declare(strict_types=1);

namespace HttpClientBinder\Method;

use ReflectionClass;

final class ReflectionMethodsProviderFactory implements MethodsProviderFactoryInterface
{
    public function build(string $className): MethodsProviderInterface
    {
        return new ReflectionMethodsProvider(new ReflectionClass($className));
    }
}