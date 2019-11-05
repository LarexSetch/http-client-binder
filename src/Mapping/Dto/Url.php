<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Dto;

final class Url
{
    /**
     * @var string
     */
    private $value;

    /**
     * @var UrlParameterBag
     */
    private $parameterBag;

    /**
     * Url constructor.
     * @param string $value
     * @param UrlParameterBag $parameterBag
     */
    public function __construct(string $value, UrlParameterBag $parameterBag)
    {
        $this->value = $value;
        $this->parameterBag = $parameterBag;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getParameterBag(): UrlParameterBag
    {
        return $this->parameterBag;
    }
}