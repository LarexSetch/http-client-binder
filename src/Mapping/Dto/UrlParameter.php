<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Dto;

final class UrlParameter
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var mixed|array|string|int
     */
    private $value;

    /**
     * UrlParameter constructor.
     * @param string $key
     * @param array|int|mixed|string $value
     */
    public function __construct(string $key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getValue()
    {
        return $this->value;
    }
}