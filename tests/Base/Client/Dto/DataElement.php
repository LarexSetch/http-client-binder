<?php

declare(strict_types=1);

namespace HttpClientBinder\Tests\Base\Client\Dto;

use JMS\Serializer\Annotation as Serializer;

class DataElement
{
    /**
     * @var int
     *
     * @Serializer\SerializedName("id")
     * @Serializer\Type("integer")
     */
    private $id;

    /**
     * @var string
     *
     * @Serializer\SerializedName("propertyOne")
     * @Serializer\Type("string")
     */
    private $propertyOne;

    /**
     * @var string
     *
     * @Serializer\SerializedName("propertyTwo")
     * @Serializer\Type("string")
     */
    private $propertyTwo;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getPropertyOne(): string
    {
        return $this->propertyOne;
    }

    public function setPropertyOne(string $propertyOne): self
    {
        $this->propertyOne = $propertyOne;

        return $this;
    }

    public function getPropertyTwo(): string
    {
        return $this->propertyTwo;
    }

    public function setPropertyTwo(string $propertyTwo): self
    {
        $this->propertyTwo = $propertyTwo;

        return $this;
    }
}