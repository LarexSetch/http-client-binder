<?php

declare(strict_types=1);

namespace HttpClientBinder\Utils;

use GuzzleHttp\Psr7\Stream;
use Psr\Http\Message\StreamInterface;

final class StringToStream
{
    public static function create(string $data): StreamInterface
    {
        $stream = fopen('php://memory', 'r+');
        fwrite($stream, $data);
        rewind($stream);

        return new Stream($stream);
    }
}
