<?php

namespace HttpClientBinder\Mapping\Factory;

use HttpClientBinder\Mapping\Dto\Client;

interface MappingFactoryInterface
{
    public function build(): Client;
}