<?php

declare(strict_types=1);

namespace HttpClientBinder\Tests\Base\Client\Dto;

use JMS\Serializer\Annotation as Serializer;

class CreateDataRequest
{
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