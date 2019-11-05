<?php

declare(strict_types=1);

namespace HttpClientBinder\Provider\Dto;

class Argument
{
    /**
     * @var string
     */
    private $name;

    /**
     * @todo enum
     * @var string|null
     */
    private $type;

    /**
     * Argument constructor.
     * @param string $name
     * @param string $type
     */
    public function __construct(string $name, ?string $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): ?string
    {
        return $this->type;
    }
}