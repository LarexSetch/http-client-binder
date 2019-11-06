<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\RequestBuilder;

use GuzzleHttp\Psr7\Request;
use HttpClientBinder\Mapping\Dto\Endpoint;
use HttpClientBinder\Protocol\RequestBuilder\BodyResolver\BodyResolver;
use HttpClientBinder\Protocol\RequestBuilder\UrlResolver\UrlResolver;
use Psr\Http\Message\RequestInterface;

final class GuzzleRequestBuilder implements RequestBuilder
{
    /**
     * @var UrlResolver
     */
    private $urlResolver;

    /**
     * @var BodyResolver
     */
    private $bodyResolver;

    public function __construct(UrlResolver $urlResolver, BodyResolver $bodyResolver)
    {
        $this->urlResolver = $urlResolver;
        $this->bodyResolver = $bodyResolver;
    }

    public function build(Endpoint $endpoint, array $arguments): RequestInterface
    {
        return
            new Request(
                $endpoint->getMethod()->toString(),
                $this->urlResolver->resolve($endpoint, $arguments),
                $this->resolveHeaders($endpoint),
                $this->bodyResolver->resolve($endpoint, $arguments)
            );
    }

    private function resolveHeaders(Endpoint $endpoint): array
    {
        $headers = [];
        foreach ($endpoint->getHeaderBag()->getHeaders() as $header) {
            $headers[$header->getName()] = $header->getValue();
        }

        return $headers;
    }
}