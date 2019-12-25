<?php

declare(strict_types=1);

namespace HttpClientBinder\Tests\Base\Client\Dto;

use JMS\Serializer\Annotation as Serializer;

class DataListResponse
{
    /**
     * @var DataElement[]
     *
     * @Serializer\SerializedName("elements")
     * @Serializer\Type("array<HttpClientBinder\Tests\Base\Client\Dto\DataElement>")
     */
    private $elements;

    /**
     * @var int
     *
     * @Serializer\SerializedName("elementsCount")
     * @Serializer\Type("integer")
     */
    private $elementsCount;

    /**
     * @var int
     *
     * @Serializer\SerializedName("elementsOnPage")
     * @Serializer\Type("integer")
     */
    private $elementsOnPage;

    public function getElements(): array
    {
        return $this->elements;
    }

    public function setElements(array $elements): self
    {
        $this->elements = $elements;

        return $this;
    }

    public function getElementsCount(): int
    {
        return $this->elementsCount;
    }

    public function setElementsCount(int $elementsCount): self
    {
        $this->elementsCount = $elementsCount;

        return $this;
    }

    public function getElementsOnPage(): int
    {
        return $this->elementsOnPage;
    }

    public function setElementsOnPage(int $elementsOnPage): self
    {
        $this->elementsOnPage = $elementsOnPage;

        return $this;
    }
}