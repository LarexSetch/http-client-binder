<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Dto;

use JMS\Serializer\Annotation as Serializer;

final readonly class EndpointBag
{
    /**
     * @param Endpoint[] $endpoints
     */
    public function __construct(
        #[Serializer\Type("array<" . Endpoint::class . ">")]
        #[Serializer\SerializedName("endpoints")]
        public array $endpoints
    ) {
    }

    /**
     * @return Endpoint[]
     */
    public function getEndpoints(): array
    {
        return $this->endpoints;
    }
}