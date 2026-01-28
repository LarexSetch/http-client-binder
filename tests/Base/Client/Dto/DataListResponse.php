<?php

declare(strict_types=1);

namespace HttpClientBinder\Tests\Base\Client\Dto;

use JMS\Serializer\Annotation as Serializer;

readonly class DataListResponse
{
    public function __construct(
        #[Serializer\SerializedName("elements")]
        #[Serializer\Type("array<HttpClientBinder\Tests\Base\Client\Dto\DataElement>")]
        private array $elements,
        #[Serializer\SerializedName("elementsCount")]
        #[Serializer\Type("integer")]
        private int $elementsCount,
        #[Serializer\SerializedName("elementsOnPage")]
        #[Serializer\Type("integer")]
        private int $elementsOnPage,
    ) {
    }

    public function getElements(): array
    {
        return $this->elements;
    }

    public function getElementsCount(): int
    {
        return $this->elementsCount;
    }

    public function getElementsOnPage(): int
    {
        return $this->elementsOnPage;
    }
}