<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Dto;

use JMS\Serializer\Annotation as Serializer;

final class UrlParameter
{
    public const TYPE_QUERY = 'query';
    public const TYPE_PATH = 'path';

    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("name")
     */
    private $argument;

    /**
     * @var int
     *
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("argumentIndex")
     */
    private $argumentIndex;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("type")
     */
    private $type;

    /**
     * @var string|null
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("alias")
     */
    private $alias;

    public function __construct(string $argument, int $argumentIndex, string $type, ?string $alias = null)
    {
        $this->argument = $argument;
        $this->argumentIndex = $argumentIndex;
        $this->type = $type;
        $this->alias = $alias;
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