<?php

declare(strict_types=1);

namespace HttpClientBinder\Builder;

use Psr\Http\Message\RequestInterface;

interface RequestBuilder
{
    public function build(): RequestInterface;
}