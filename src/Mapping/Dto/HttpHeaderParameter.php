<?php

namespace HttpClientBinder\Mapping\Dto;

use JMS\Serializer\Annotation as Serializer;

final class HttpHeaderParameter
{
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
     * @var string|null
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("alias")
     */
    private $alias;

    public function __construct(string $argument, int $argumentIndex, ?string $alias)
    {
        $this->argument = $argument;
        $this->argumentIndex = $argumentIndex;
        $this->alias = $alias;
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