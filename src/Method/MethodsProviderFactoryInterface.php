<?php

declare(strict_types=1);

namespace HttpClientBinder\Method;

use HttpClientBinder\Mapping\Factory\Provider\MethodsProviderInterface;

interface MethodsProviderFactoryInterface
{
    public function build(string $className): MethodsProviderInterface;
}