<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\ResponseAssembler;

use HttpClientBinder\Mapping\Dto\Endpoint;
use Psr\Http\Message\ResponseInterface;

final class Deserialize implements ResponseAssembler
{
    public function assemble(ResponseInterface $response, Endpoint $endpoint)
    {
        // TODO: Implement assemble() method.
    }
}