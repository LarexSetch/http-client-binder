<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Dto;

final class HttpHeader
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string[]
     */
    private $value;

    public function __construct(string $name, array $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public function getValue(): array
    {
        return $this->value;
    }
}