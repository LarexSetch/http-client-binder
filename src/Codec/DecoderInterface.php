<?php

declare(strict_types=1);

namespace HttpClientBinder\Codec;

use Psr\Http\Message\StreamInterface;

interface DecoderInterface
{
    public function decode(StreamInterface $stream, string $className, Type $type): mixed;
}