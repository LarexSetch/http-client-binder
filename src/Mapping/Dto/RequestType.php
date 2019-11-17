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
     * @var string
     *
     * @Serializer\Type("string")
     * @Serializer\SerializedName("type")
     */
    private $type;

    public function __construct(string $argument, string $type)
    {
        $this->argument = $argument;
        $this->type = $type;
    }

    public function getArgument(): string
    {
        return $this->argument;
    }

    public function getType(): string
    {
        return $this->type;
    }
}