<?php

declare(strict_types=1);

namespace HttpClientBinder\Provider\Dto;

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
     * @var RequestMapping
     */
    private $request;

    /**
     * @var HeaderBag|null
     */
    private $headersBag;

    /**
     * @var ParameterBag|null
     */
    private $parametersBag;

    /**
     * @var RequestBody|null
     */
    private $requestBody;

    /**
     * @var string
     */
    private $responseType;

    /**
     * @var Argument[]
     */
    private $arguments;

    public function __construct(
        string $name,
        RequestMapping $request,
        ?HeaderBag $headersBag,
        ?ParameterBag $parametersBag,
        ?RequestBody $requestBody,
        ?string $responseType,
        array $arguments
    ) {
        $this->name = $name;
        $this->request = $request;
        $this->headersBag = $headersBag;
        $this->parametersBag = $parametersBag;
        $this->requestBody = $requestBody;
        $this->responseType = $responseType;
        $this->arguments = $arguments;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRequest(): RequestMapping
    {
        return $this->request;
    }

    public function getHeadersBag(): ?HeaderBag
    {
        return $this->headersBag;
    }

    public function getParametersBag(): ?ParameterBag
    {
        return $this->parametersBag;
    }

    public function getRequestBody(): ?RequestBody
    {
        return $this->requestBody;
    }

    public function getResponseType(): string
    {
        return $this->responseType;
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }
}