<?php

namespace HttpClientBinder\Mapping\Extractor;

use Doctrine\Common\Annotations\Reader;
use HttpClientBinder\Annotation\Parameter;
use HttpClientBinder\Annotation\ParameterBag;
use HttpClientBinder\Annotation\RequestBody;
use HttpClientBinder\Mapping\Dto\RequestType;
use ReflectionMethod;
use ReflectionParameter;
use DomainException;

final class RequestTypeExtractor implements RequestTypeExtractorInterface
{
    /**
     * @var Reader
     */
    private $annotationReader;

    public function __construct(Reader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    public function extract(ReflectionMethod $method): ?RequestType
    {
        /** @var ParameterBag $parametersBag */
        $parametersBag = $this->annotationReader->getMethodAnnotation($method, ParameterBag::class);
        if (null === $parametersBag) {
            return null;
        }

        foreach ($parametersBag->getParameters() as $parameter) {
            if (in_array(Parameter::TYPE_BODY, $parameter->getTypes())) {
                return $this->createRequestType($this->getReflectionParameter($method, $parameter));
            }
        }

        return null;
    }

    private function getReflectionParameter(ReflectionMethod $method, Parameter $parameter): ReflectionParameter
    {
        foreach ($method->getParameters() as $reflectionParameter) {
            if ($reflectionParameter->getName() === $parameter->getArgumentName()) {
                return $reflectionParameter;
            }
        }

        throw new DomainException(sprintf(
            "Cannot find parameter %s in method %s",
            $parameter->getArgumentName(),
            $method->getName()
        ));
    }

    private function createRequestType(ReflectionParameter $parameter): RequestType
    {
        $type = $parameter->getType();
        if (null === $type) {
            throw new DomainException(sprintf("You must define argument type for %s", $parameter->getName()));
        }

        return
            new RequestType(
                $parameter->getName(),
                $parameter->getPosition(),
                $type->getName()
            );
    }
}