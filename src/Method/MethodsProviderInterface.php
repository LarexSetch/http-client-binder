<?php

declare(strict_types=1);


namespace HttpClientBinder\Mapping\Factory\Provider;

use HttpClientBinder\Mapping\Factory\Provider\Dto\Method;

interface MethodsProviderInterface
{
    /**
     * @return Method[]
     */
    public function provide(): array;
}