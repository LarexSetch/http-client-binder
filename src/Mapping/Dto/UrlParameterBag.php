<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Dto;

final class UrlParameterBag
{
    /**
     * @var UrlParameter[]
     */
    private $parameters;

    /**
     * UrlParameterBag constructor.
     * @param UrlParameter[] $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }
}