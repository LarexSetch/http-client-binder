<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Dto;

final class UrlParameterBag
{
    /**
     * @var UrlParameter[]
     */
    private $parameters;

    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @return UrlParameter[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}