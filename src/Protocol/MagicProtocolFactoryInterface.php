<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol;

interface MagicProtocolFactoryInterface
{
    /**
     * @param string $jsonMappings
     * @return MagicProtocol
     */
    public function build(string $jsonMappings): MagicProtocol;
}