<?php

declare(strict_types=1);

namespace HttpClientBinder\Fabrics\Mapping;

use HttpClientBinder\Mapping\MappingBuilderInterface;

interface MappingBuilderFactoryInterface
{
    public function create(string $className): MappingBuilderInterface;
}