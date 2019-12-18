<?php

declare(strict_types=1);

namespace HttpClientBinder\Proxy;

use HttpClientBinder\Proxy\Dto\RenderData;

interface RenderDataFactoryInterface
{
    public function build(string $interfaceName): RenderData;
}