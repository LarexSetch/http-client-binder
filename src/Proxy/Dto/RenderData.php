<?php

declare(strict_types=1);

namespace HttpClientBinder\Proxy\Dto;

use HttpClientBinder\Mapping\Factory\Provider\Dto\Method;

final class RenderData
{
    /**
     * @var string
     */
    private $className;

    /**
     * @var string
     */
    private $interfaceName;

    /**
     * @var string
     */
    private $protocolClassName;

    /**
     * @var string
     */
    private $jsonMappings;

    /**
     * @var Method[]
     */
    private $methods;

    public function __construct(
        string $className,
        string $interfaceName,
        string $protocolClassName,
        string $jsonMappings,
        array $methods
    ) {
        $this->className = $className;
        $this->interfaceName = $interfaceName;
        $this->protocolClassName = $protocolClassName;
        $this->jsonMappings = $jsonMappings;
        $this->methods = $methods;
    }
}