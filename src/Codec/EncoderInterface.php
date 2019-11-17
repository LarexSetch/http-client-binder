<?php

declare(strict_types=1);

namespace HttpClientBinder\Codec;

use Psr\Http\Message\StreamInterface;

interface EncoderInterface
{
    /**
     * @param mixed $object
     */
    public function encode($object, Type $type): StreamInterface;
}