<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Dto;

use JMS\Serializer\Annotation as Serializer;

final class RequestType
{
    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("argument")
     */
    private $argument;

    /**
     * @var int
     *
     * @Serializer\Type("integer")
     * @Serializer\SerializedName("index")
     */
    private $index;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("type")
     */
    private $type;

    public function __construct(string $argument, int $index, string $type)
    {
        $this->argument = $argument;
        $this->index = $index;
        $this->type = $type;
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