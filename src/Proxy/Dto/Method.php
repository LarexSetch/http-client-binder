<?php

declare(strict_types=1);

namespace HttpClientBinder\Proxy\Dto;

final readonly class Method
{
    public function __construct(
        private string $name,
        private string $returnType,
        private array $arguments
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getReturnType(): string
    {
        return $this->returnType;
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }
}