<?php

declare(strict_types=1);

namespace HttpClientBinder\Method\Dto;

use HttpClientBinder\Annotation\HeaderBag;
use HttpClientBinder\Annotation\ParameterBag;
use HttpClientBinder\Annotation\RequestBody;
use HttpClientBinder\Annotation\RequestMapping;

class Method
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $responseType;

    /**
     * @var Argument[]
     */
    private $arguments;

    public function __construct(string $name, string $responseType, array $arguments)
    {
        $this->name = $name;
        $this->responseType = $responseType;
        $this->arguments = $arguments;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getResponseType(): string
    {
        return $this->responseType;
    }

    /**
     * @return Argument[]
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }
}