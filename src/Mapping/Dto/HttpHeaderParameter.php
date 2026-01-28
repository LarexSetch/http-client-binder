<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Dto;

use JMS\Serializer\Annotation as Serializer;

final readonly class HttpHeaderParameter
{
    public function __construct(
        #[Serializer\Type("string")]
        #[Serializer\SerializedName("name")]
        public string $argument,

        #[Serializer\Type("integer")]
        #[Serializer\SerializedName("argumentIndex")]
        public int $argumentIndex,

        #[Serializer\Type("string")]
        #[Serializer\SerializedName("alias")]
        public ?string $alias
    ) {
    }

    /**
     * @return string
     */
    public function getArgument(): string
    {
        return $this->argument;
    }

    /**
     * @return int
     */
    public function getArgumentIndex(): int
    {
        return $this->argumentIndex;
    }

    /**
     * @return string|null
     */
    public function getAlias(): ?string
    {
        return $this->alias;
    }
}