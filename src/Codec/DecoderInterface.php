<?php

declare(strict_types=1);

namespace HttpClientBinder\Codec;

use Psr\Http\Message\StreamInterface;

interface DecoderInterface
{
    /**
     * @return mixed
     */
    public function decode(StreamInterface $stream, Type $type);
}