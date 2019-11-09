<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping;

use HttpClientBinder\Mapping\Dto\Client;

interface MappingBuilderInterface
{
    public function build(): Client;
}