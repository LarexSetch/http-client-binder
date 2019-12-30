<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\RemoteCall;

use GuzzleHttp\ClientInterface;
use HttpClientBinder\Codec\DecoderInterface;
use HttpClientBinder\Mapping\Dto\Endpoint;
use HttpClientBinder\Protocol\RemoteCall\RequestBuilder\RequestBuilderInterface;
use HttpClientBinder\Protocol\RemoteCall\ResponseDecoder\ResponseTypeBuilder;

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

    /**
     * @var RequestInterceptorInterface
     */
    private $requestInterceptor;

    public function __construct(
        ClientInterface $client,
        RequestBuilderInterface $requestBuilder,
        DecoderInterface $decoder,
        RequestInterceptorInterface $requestInterceptor
    ) {
        $this->client = $client;
        $this->requestBuilder = $requestBuilder;
        $this->decoder = $decoder;
        $this->requestInterceptor = $requestInterceptor;
    }

    public function invoke(Endpoint $endpoint, array $arguments)
    {
        $request = $this->requestBuilder->build($endpoint, $arguments);
        $response = $this->client->send($this->requestInterceptor->intercept($request));

        return
            $this->decoder->decode(
                $response->getBody(),
                ResponseTypeBuilder::create($response)->build($endpoint)
            );
    }
}