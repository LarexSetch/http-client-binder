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
    public function __construct(
        private readonly ClientInterface $client,
        private readonly RequestBuilderInterface $requestBuilder,
        private readonly DecoderInterface $decoder,
        private readonly RequestInterceptorInterface $requestInterceptor
    ) {
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