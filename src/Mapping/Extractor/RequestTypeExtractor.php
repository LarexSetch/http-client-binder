<?php

namespace HttpClientBinder\Mapping\Extractor;

use DomainException;
use HttpClientBinder\Annotation\Parameter;
use HttpClientBinder\Annotation\ParameterType;
use HttpClientBinder\Mapping\Dto\RequestType;
use ReflectionMethod;
use ReflectionParameter;

final class RequestTypeExtractor implements RequestTypeExtractorInterface
{
    public function extract(ReflectionMethod $method): ?RequestType
    {
        $parameters = $method->getAttributes(Parameter::class);
        if (empty($parameters)) {
            return null;
        }

        foreach ($parameters as $reflectionAttribute) {
            /** @var Parameter $parameter */
            $parameter = $reflectionAttribute->newInstance();
            if ($parameter->type === ParameterType::BODY) {
                return $this->createRequestType($this->getReflectionParameter($method, $parameter));
            }
        }

        return null;
    }

    private function getReflectionParameter(ReflectionMethod $method, Parameter $parameter): ReflectionParameter
    {
        foreach ($method->getParameters() as $reflectionParameter) {
            if ($reflectionParameter->getName() === $parameter->argumentName) {
                return $reflectionParameter;
            }
        }

        throw new DomainException(
            sprintf(
                "Cannot find parameter %s in method %s",
                $parameter->argumentName,
                $method->getName()
            )
        );
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