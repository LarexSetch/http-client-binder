<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Dto;

use JMS\Serializer\Annotation as Serializer;

final readonly class RequestType
{
    public function __construct(
        #[Serializer\Type("string")]
        #[Serializer\SerializedName("argument")]
        public string $argument,

        #[Serializer\Type("integer")]
        #[Serializer\SerializedName("index")]
        public int $index,

        #[Serializer\Type("string")]
        #[Serializer\SerializedName("type")]
        public string $type
    ) {
    }

    public function getArgument(): string
    {
        return $this->argument;
    }

    public function getIndex(): int
    {
        return $this->index;
    }

    public function getType(): string
    {
        return $this->type;
    }
}