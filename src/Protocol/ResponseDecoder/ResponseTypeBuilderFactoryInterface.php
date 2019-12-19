<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\ResponseDecoder;

use HttpClientBinder\Codec\TypeBuilderInterface;
use Psr\Http\Message\ResponseInterface;

interface ResponseTypeBuilderFactoryInterface
{
    public static function create(ResponseInterface $response): TypeBuilderInterface;
}