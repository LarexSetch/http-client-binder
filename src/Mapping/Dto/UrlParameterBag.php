<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Dto;

use JMS\Serializer\Annotation as Serializer;

final class UrlParameterBag
{
    /**
     * @var UrlParameter[]
     *
     * @Serializer\Type("array<HttpClientBinder\Mapping\Dto\UrlParameter>")
     * @Serializer\SerializedName("parameters")
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