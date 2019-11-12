<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Dto;

use JMS\Serializer\Annotation as Serializer;

final class Url
{
    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("pattern")
     */
    private $value;

    /**
     * @var UrlParameterBag
     *
     * @Serializer\Type("HttpClientBinder\Mapping\Dto\UrlParameterBag")
     * @Serializer\SerializedName("parameterBag")
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