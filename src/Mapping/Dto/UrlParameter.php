<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Dto;

use HttpClientBinder\Mapping\Enum\UrlParameterType;

final class UrlParameter
{
    /**
     * @var UrlParameterType
     */
    private $type;

    /**
     * @var string
     */
    private $key;

    public function __construct(UrlParameterType $parameterType, string $key)
    {
        $this->type = $parameterType;
        $this->key = $key;
    }

    public function getType(): UrlParameterType
    {
        return $this->type;
    }

    public function getKey(): string
    {
        return $this->key;
    }
}