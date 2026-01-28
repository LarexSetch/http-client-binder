<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Dto;

use JMS\Serializer\Annotation as Serializer;

final readonly class UrlParameter
{
    public const TYPE_QUERY = 'query';
    public const TYPE_PATH = 'path';

    public function __construct(
        #[Serializer\Type("string")]
        #[Serializer\SerializedName("name")]
        public string $argument,

        #[Serializer\Type("integer")]
        #[Serializer\SerializedName("argumentIndex")]
        public int $argumentIndex,

        #[Serializer\Type("string")]
        #[Serializer\SerializedName("type")]
        public string $type,

        #[Serializer\Type("string")]
        #[Serializer\SerializedName("alias")]
        public ?string $alias = null
    ) {
    }

    public function getArgument(): string
    {
        return $this->argument;
    }

    public function getArgumentIndex(): int
    {
        return $this->argumentIndex;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public static function getTypes(): array
    {
        return [self::TYPE_PATH, self::TYPE_QUERY];
    }
}