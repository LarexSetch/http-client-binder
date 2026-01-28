<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Dto;

use JMS\Serializer\Annotation as Serializer;

final readonly class HttpHeader
{
    /**
     * @param string[] $value
     * @param HttpHeaderParameter[] $parameters
     */
    public function __construct(
        #[Serializer\Type("string")]
        #[Serializer\SerializedName("name")]
        public string $name,

        #[Serializer\Type("array<string>")]
        #[Serializer\SerializedName("value")]
        public array $value,

        #[Serializer\Type("array<" . HttpHeaderParameter::class . ">")]
        #[Serializer\SerializedName("parameters")]
        public array $parameters = []
    ) {
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
     * @return self
     */
    public function withParameters(array $parameters): self
    {
        return new self($this->name, $this->value, $parameters);
    }
}