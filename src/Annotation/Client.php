<?php

declare(strict_types=1);

namespace HttpClientBinder\Annotation;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Client
{
    /**
     * @param Header[] $headers
     */
    public function __construct(
        public ?string $baseUrl,
        public array $headers = []
    ) {
    }
}