<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol;

interface MagicProtocolInterface
{
    /**
     * @throws UnexpectedEndpointException
     */
    public function __call(string $name, array $arguments);
}