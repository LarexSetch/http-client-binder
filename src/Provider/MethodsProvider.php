<?php

declare(strict_types=1);


namespace HttpClientBinder\Provider;

use HttpClientBinder\Provider\Dto\Method;

interface MethodsProvider
{
    /**
     * @return Method[]
     */
    public function provide(): array;
}