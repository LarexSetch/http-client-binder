<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\ResponseDecoder;

use HttpClientBinder\Codec\TypeBuilderInterface;
use Psr\Http\Message\ResponseInterface;

final class ResponseTypeBuilderFactory implements ResponseTypeBuilderFactoryInterface
{
    public function create(ResponseInterface $response): TypeBuilderInterface
    {
        return new ResponseTypeBuilder($response);
    }
}