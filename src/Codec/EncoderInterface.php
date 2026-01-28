<?php

declare(strict_types=1);

namespace HttpClientBinder\Codec;

use Psr\Http\Message\StreamInterface;

interface EncoderInterface
{
    public function encode(mixed $object, Type $type): StreamInterface;
}