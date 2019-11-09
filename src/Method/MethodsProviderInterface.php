<?php

declare(strict_types=1);

namespace HttpClientBinder\Method;

use HttpClientBinder\Method\Dto\Method;

interface MethodsProviderInterface
{
    /**
     * @return Method[]
     */
    public function provide(): array;
}