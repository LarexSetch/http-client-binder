<?php

declare(strict_types=1);

namespace HttpClientBinder\Codec;

final class Type
{
    public const FORMAT_JSON = 'json';
    public const FORMAT_XML = 'xml';
    public const FORMAT_TEXT = 'text';

    /**
     * @var string
     */
    private $format;

    /**
     * @var string
     */
    private $className;

    public function __construct(string $format, string $className)
    {
        $this->format = $format;
        $this->className = $className;
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function getClassName(): string
    {
        return $this->className;
    }
}