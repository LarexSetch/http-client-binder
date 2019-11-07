<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\ResponseAssembler;

use HttpClientBinder\Mapping\Dto\Endpoint;
use Psr\Http\Message\ResponseInterface;

interface ResponseAssembler
{
    public function assemble(ResponseInterface $response, Endpoint $endpoint);
}