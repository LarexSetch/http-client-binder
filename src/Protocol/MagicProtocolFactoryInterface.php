<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol;

interface MagicProtocolFactoryInterface
{
    public function build(string $jsonMappings): MagicProtocolInterface;
}