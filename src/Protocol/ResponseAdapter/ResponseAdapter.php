<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\ResponseAdapter;

use HttpClientBinder\Mapping\Dto\Endpoint;
use Psr\Http\Message\ResponseInterface;

interface ResponseAdapter
{
    public function assemble(ResponseInterface $response, Endpoint $endpoint);
}