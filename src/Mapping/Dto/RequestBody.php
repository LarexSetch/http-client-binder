<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Dto;

use HttpClientBinder\Mapping\Enum\SerializationType;

final class RequestBody
{
    /**
     * @var string
     */
    private $className;

    /**
     * @var string
     */
    private $argumentName;

    /**
     * @var SerializationType
     */
    private $serializationType;

    /**
     * RequestBody constructor.
     * @param string $className
     * @param string $argumentName
     * @param SerializationType $serializationType
     */
    public function __construct(string $className, string $argumentName, SerializationType $serializationType)
    {
        $this->className = $className;
        $this->argumentName = $argumentName;
        $this->serializationType = $serializationType;
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getArgumentName(): string
    {
        return $this->argumentName;
    }

    public function getSerializationType(): SerializationType
    {
        return $this->serializationType;
    }
}