<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\RemoteCall;

use GuzzleHttp\ClientInterface;
use HttpClientBinder\Codec\DecoderInterface;
use HttpClientBinder\Mapping\Dto\Endpoint;
use HttpClientBinder\Protocol\RequestBuilder\RequestBuilderInterface;
use HttpClientBinder\Protocol\ResponseDecoder\ResponseTypeBuilder;
use Psr\Http\Message\ResponseInterface;

final class RemoteCall implements RemoteCallInterface
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var DecoderInterface
     */
    private $decoder;

    /**
     * @var RequestBuilderInterface
     */
    private $requestBuilder;

    public function __construct(
        ClientInterface $client,
        RequestBuilderInterface $requestBuilder,
        DecoderInterface $decoder
    ) {
        $this->client = $client;
        $this->requestBuilder = $requestBuilder;
        $this->decoder = $decoder;
    }

    public function invoke(Endpoint $endpoint, array $arguments)
    {
        $request = $this->requestBuilder->build($endpoint, $arguments);
        $response = $this->client->send($request);

        return
            $this->decoder->decode(
                $response->getBody(),
                ResponseTypeBuilder::create($response)->build($endpoint)
            );
    }
}