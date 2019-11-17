<?php

declare(strict_types=1);

namespace HttpClientBinder\Codec;

use HttpClientBinder\Mapping\Dto\Endpoint;

interface TypeBuilderInterface
{
    /**
     * @throws UnexpectedFormatException
     */
    public function build(Endpoint $endpoint): Type;
}