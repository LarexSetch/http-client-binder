<?php

declare(strict_types=1);

namespace HttpClientBinder\Proxy\Dto;

final readonly class RenderData
{
    public function __construct(
        private string $className,
        private string $interfaceName,
        private string $protocolClassName,
        private string $jsonMappings,
        private array $methods
    ) {
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getInterfaceName(): string
    {
        return $this->interfaceName;
    }

    public function getProtocolClassName(): string
    {
        return $this->protocolClassName;
    }

    public function getJsonMappings(): string
    {
        return $this->jsonMappings;
    }

    public function getMethods(): array
    {
        return $this->methods;
    }
}