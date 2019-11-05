<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Dto;

use HttpClientBinder\Mapping\Enum\SerializationType;

final class ResponseBody
{
    /**
     * @var string
     */
    private $className;

    /**
     * @var SerializationType
     */
    private $deserializationType;

    /**
     * ResponseBody constructor.
     * @param string $className
     * @param SerializationType $deserializationType
     */
    public function __construct(string $className, SerializationType $deserializationType)
    {
        $this->className = $className;
        $this->deserializationType = $deserializationType;
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getDeserializationType(): SerializationType
    {
        return $this->deserializationType;
    }
}