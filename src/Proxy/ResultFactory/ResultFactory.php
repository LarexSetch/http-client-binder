<?php

declare(strict_types=1);

namespace HttpClientBinder\Proxy\ResultFactory;

use HttpClientBinder\Metadata\Dto\Endpoint;
use Psr\Http\Message\ResponseInterface;

interface ResultFactory
{
    /**
     * @return mixed deserialized object or stream
     */
    public function create(Endpoint $endpoint, ResponseInterface $response): mixed;
}
