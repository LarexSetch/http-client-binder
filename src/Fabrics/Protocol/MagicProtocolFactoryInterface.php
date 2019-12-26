<?php

declare(strict_types=1);

namespace HttpClientBinder\Fabrics\Protocol;

use HttpClientBinder\Protocol\MagicProtocolInterface;

interface MagicProtocolFactoryInterface
{
    public function build(string $jsonMappings): MagicProtocolInterface;
}