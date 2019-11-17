<?php

declare(strict_types=1);

namespace HttpClientBinder\Protocol\RequestBuilder;

use Psr\Http\Message\StreamInterface;

interface StreamBuilderInterface
{
    public function build(string $body): StreamInterface;
}