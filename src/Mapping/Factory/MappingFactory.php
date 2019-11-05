<?php

namespace HttpClientBinder\Mapping\Factory;

use HttpClientBinder\Mapping\Dto\Client;

interface MappingFactory
{
    public function build(): Client;
}