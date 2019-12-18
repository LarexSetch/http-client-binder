<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\RequestBuilder;

use GuzzleHttp\Psr7\Request;
use HttpClientBinder\Mapping\Dto\Endpoint;
use Psr\Http\Message\RequestInterface;

final class GuzzleRequestBuilder implements RequestBuilderInterface
{
    /**
     * @var UrlBuilderInterface
     */
    private $urlBuilder;

    /**
     * @var BodyResolverInterface
     */
    private $bodyResolver;

    public function __construct(UrlBuilderInterface $urlBuilder, BodyResolverInterface $bodyResolver)
    {
        $this->urlBuilder = $urlBuilder;
        $this->bodyResolver = $bodyResolver;
    }

    public function build(Endpoint $endpoint, array $arguments): RequestInterface
    {
        return
            new Request(
                $endpoint->getMethod(),
                $this->urlBuilder->build($endpoint, $arguments),
                $this->assembleHeaders($endpoint),
                $this->bodyResolver->resolve($endpoint, $arguments)
            );
    }

    private function assembleHeaders(Endpoint $endpoint): array
    {
        $headers = [];
        foreach ($endpoint->getHeaderBag()->getHeaders() as $header) {
            $headers[$header->getName()] = $header->getValue();
        }

        return $headers;
    }
}