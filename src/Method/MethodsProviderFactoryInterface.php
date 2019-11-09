<?php

declare(strict_types=1);

namespace HttpClientBinder\Method;

interface MethodsProviderFactoryInterface
{
    public function build(string $className): MethodsProviderInterface;
}