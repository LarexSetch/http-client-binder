<?php

declare(strict_types=1);

namespace HttpClientBinder\Codec;

enum Type: string
{
    case JSON = 'json';
    case XML = 'xml';
    case TEXT = 'text';
}