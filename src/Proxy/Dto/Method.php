<?php

declare(strict_types=1);

namespace HttpClientBinder\Proxy\Dto;

final class Method
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $returnType;

    /**
     * @var MethodArgument[]
     */
    private $arguments;

    public function __construct(string $name, string $returnType, array $arguments)
    {
        $this->name = $name;
        $this->returnType = $returnType;
        $this->arguments = $arguments;
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