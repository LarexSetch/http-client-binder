<?php

declare(strict_types=1);

namespace HttpClientBinder\Codec;

readonly final class Type
{
    public const FORMAT_JSON = 'json';
    public const FORMAT_XML = 'xml';
    public const FORMAT_TEXT = 'text';

    public function __construct(
        public string $format,
        public string $className
    ) {
    }
}