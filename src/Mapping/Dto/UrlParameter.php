<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Dto;

use HttpClientBinder\Mapping\Enum\UrlParameterType;

final class UrlParameter
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $alias;

    /**
     * @var UrlParameterType
     */
    private $type;

    /**
     * UrlParameter constructor.
     * @param string $name
     * @param string $alias
     * @param UrlParameterType $type
     */
    public function __construct(string $name, string $alias, UrlParameterType $type)
    {
        $this->name = $name;
        $this->alias = $alias;
        $this->type = $type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    public function getType(): UrlParameterType
    {
        return $this->type;
    }
}