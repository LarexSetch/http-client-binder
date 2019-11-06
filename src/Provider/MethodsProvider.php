<?php

declare(strict_types=1);


namespace HttpClientBinder\Provider;

use HttpClientBinder\Provider\Dto\Method;
use HttpClientBinder\Provider\Exception\BaseMethodsProviderException;

interface MethodsProvider
{
    /**
     * @return Method[]
     * @throws BaseMethodsProviderException
     */
    public function provide(): array;
}