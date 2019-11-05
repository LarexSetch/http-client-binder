<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Dto;

final class HttpHeader
{
    /**
     * @var string
     */
    private $value;

    /**
     * HttpHeader constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}