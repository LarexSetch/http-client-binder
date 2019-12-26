<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\RequestBuilder;

use GuzzleHttp\Psr7\Request;
use HttpClientBinder\Mapping\Dto\Endpoint;
use HttpClientBinder\Mapping\Dto\HttpHeader;
use HttpClientBinder\Mapping\Dto\HttpHeaderParameter;
use HttpClientBinder\Mapping\Dto\UrlParameter;
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
                $this->assembleHeaders($endpoint, $arguments),
                $this->bodyResolver->resolve($endpoint, $arguments)
            );
    }

    private function assembleHeaders(Endpoint $endpoint, array $arguments): array
    {
        $headers = [];
        foreach ($endpoint->getHeaderBag()->getHeaders() as $header) {
            $headers[$header->getName()] =
                array_map(
                    function (string $value) use ($header, $arguments) {
                        return $this->replaceHeaderValues($header, $value, $arguments);
                    },
                    $header->getValue()
                );
        }

        return $headers;
    }

    private function replaceHeaderValues(HttpHeader $header, string $value, array $arguments): string
    {
        $replaceData = [];
        foreach ($header->getParameters() as $parameter) {
            $this->checkArgument($parameter, $arguments);
            $key = sprintf('{%s}', $parameter->getAlias() ?? $parameter->getArgument());
            $replaceData[$key] = $arguments[$parameter->getArgumentIndex()];
        }

        return
            strtr(
                $value,
                $replaceData
            );
    }

    private function checkArgument(HttpHeaderParameter $parameter, array $arguments): void
    {
        if (!key_exists($parameter->getArgumentIndex(), $arguments)) {
            throw new \DomainException(sprintf(
                "Cannot find argument on position %s for parameter %s",
                $parameter->getArgumentIndex(),
                $parameter->getArgument()
            ));
        }

        if (!is_scalar($arguments[$parameter->getArgumentIndex()])) {
            throw new \DomainException(sprintf(
                "Argument must be scalar on position %s name %s",
                $parameter->getArgumentIndex(),
                $parameter->getArgument()
            ));
        }
    }
}