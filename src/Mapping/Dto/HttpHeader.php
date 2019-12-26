<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Dto;

use JMS\Serializer\Annotation as Serializer;

final class HttpHeader
{
    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("name")
     */
    private $name;

    /**
     * @var string[]
     *
     * @Serializer\Type("array<string>")
     * @Serializer\SerializedName("value")
     */
    private $value;

    /**
     * @var HttpHeaderParameter[]
     *
     * @Serializer\Type("array<HttpClientBinder\Mapping\Dto\HttpHeaderParameter>")
     * @Serializer\SerializedName("parameters")
     */
    private $parameters = [];

    public function __construct(string $name, array $value, array $parameters = [])
    {
        $this->name = $name;
        $this->value = $value;
        $this->parameters = $parameters;
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

    /**
     * @return HttpHeaderParameter[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @return static
     */
    public function setParameters(array $parameters): HttpHeader
    {
        $this->parameters = $parameters;

        return $this;
    }
}