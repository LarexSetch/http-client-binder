<?php

declare(strict_types=1);

namespace HttpClientBinder\Tests\Base\Client\Dto;

use JMS\Serializer\Annotation as Serializer;

readonly class CreateDataResponse
{
    public function __construct(
        #[Serializer\SerializedName("id")]
        #[Serializer\Type("integer")]
        private int $id,
        #[Serializer\SerializedName("propertyOne")]
        #[Serializer\Type("string")]
        private string $propertyOne,
        #[Serializer\SerializedName("propertyTwo")]
        #[Serializer\Type("string")]
        private string $propertyTwo,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPropertyOne(): string
    {
        return $this->propertyOne;
    }

    public function getPropertyTwo(): string
    {
        return $this->propertyTwo;
    }
}