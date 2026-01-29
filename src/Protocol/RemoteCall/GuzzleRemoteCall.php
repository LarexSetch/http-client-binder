<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\RemoteCall;

use Exception;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use HttpClientBinder\Codec\Type;
use HttpClientBinder\Codec\UnexpectedFormatException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final readonly class GuzzleRemoteCall implements RemoteCall
{
    public function __construct(
        private ClientInterface $client,
        private RequestInterceptorInterface $requestInterceptor
    ) {
    }

    public function invoke(RequestInterface $request): ResponseInterface
    {
        try {
            return $this->client->send($this->requestInterceptor->intercept($request));
        } catch (ClientException|ServerException $exception) {
            throw new Exception(
                sprintf('Handled client error [%s] code [%s]', $exception->getMessage(), $exception->getCode()),
                $exception->getCode(),
                $exception
            );
        } catch (GuzzleException $e) {
            throw new Exception(sprintf('HttpClient handle error [%s]', $e->getMessage()), $e->getCode(), $e);
        }
    }

    /**
     * @throws UnexpectedFormatException
     */
    private function getType(ResponseInterface $response): Type
    {
        foreach ($response->getHeaders() as $name => $header) {
            if ('content-type' === strtolower($name)) {
                foreach ($header as $item) {
                    return match (true) {
                        'application/json' === $item => Type::JSON,
                        'application/xml' === $item => Type::XML,
                        preg_match('/text\//', $item) => Type::TEXT,
                        default => throw new UnexpectedFormatException(sprintf('Unsupported content type %s', $item)),
                    };
                }
            }
        }

        throw new UnexpectedFormatException('The response must have Content-type header');
    }
}