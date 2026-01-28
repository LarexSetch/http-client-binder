<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Dto;

use JMS\Serializer\Annotation as Serializer;

final readonly class UrlParameterBag
{
    /**
     * @param UrlParameter[] $parameters
     */
    public function __construct(
        #[Serializer\Type("array<" . UrlParameter::class . ">")]
        #[Serializer\SerializedName("parameters")]
        public array $parameters
    ) {
    }

    /**
     * @return UrlParameter[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}