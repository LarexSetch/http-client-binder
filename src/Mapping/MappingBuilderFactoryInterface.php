<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping;

interface MappingBuilderFactoryInterface
{
    public function create(string $className): MappingBuilderInterface;
}