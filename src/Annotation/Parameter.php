<?php

declare(strict_types=1);

namespace HttpClientBinder\Annotation;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
readonly final class Parameter
{
    public function __construct(
        public string $argumentName,
        public ?string $alias = null,
        public ParameterType $type = ParameterType::QUERY,
    ) {
    }
}