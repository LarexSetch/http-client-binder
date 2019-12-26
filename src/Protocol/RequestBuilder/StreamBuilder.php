<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\RequestBuilder;

use GuzzleHttp\Psr7\Stream;
use Psr\Http\Message\StreamInterface;

final class StreamBuilder implements StreamBuilderInterface
{
    public function build(string $body): StreamInterface
    {
        $stream = fopen('php://memory', 'r+');
        fwrite($stream, $body);
        rewind($stream);

        return new Stream($stream);
    }
}