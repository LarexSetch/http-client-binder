<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping;

use HttpClientBinder\Mapping\Dto\MappingClient;

interface MappingBuilderInterface
{
    public function build(string $interfaceName): MappingClient;
}