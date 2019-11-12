<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\RemoteCall;

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class EndpointCall implements RemoteCall
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function invoke(RequestInterface $request): ResponseInterface
    {
        //TODO intercept exceptions with
        return $this->client->send($request);
    }
}