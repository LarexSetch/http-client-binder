<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Dto;

use JMS\Serializer\Annotation as Serializer;

final readonly class Url
{
    public function __construct(
        #[Serializer\Type("string")]
        #[Serializer\SerializedName("pattern")]
        public string $value,

        #[Serializer\Type(UrlParameterBag::class)]
        #[Serializer\SerializedName("parameterBag")]
        public UrlParameterBag $parameterBag
    ) {
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