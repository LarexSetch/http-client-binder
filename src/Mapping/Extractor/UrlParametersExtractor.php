<?php

namespace HttpClientBinder\Mapping\Extractor;

use DomainException;
use HttpClientBinder\Annotation\Parameter;
use HttpClientBinder\Annotation\ParameterType;
use HttpClientBinder\Mapping\Dto\UrlParameter;
use HttpClientBinder\Mapping\Dto\UrlParameterBag;
use ReflectionAttribute;
use ReflectionMethod;

final class UrlParametersExtractor implements UrlParametersExtractorInterface
{
    private const DEFAULT_PARAMETERS_TYPE = [ParameterType::QUERY, ParameterType::PATH];

    public function extract(ReflectionMethod $method): UrlParameterBag
    {
        $parameters = $method->getAttributes(Parameter::class);
        if (empty($parameters)) {
            return new UrlParameterBag([]);
        }

        return
            new UrlParameterBag(
                array_values(
                    array_map(
                        fn(Parameter $parameter) => new UrlParameter(
                            $parameter->argumentName,
                            $this->resolveArgumentIndex($method, $parameter),
                            $this->resolveType($parameter),
                            $parameter->alias
                        ),
                        array_values(
                            array_filter(
                                array_map(
                                    fn(ReflectionAttribute $attribute) => $attribute->newInstance(),
                                    $parameters
                                ),
                                fn(Parameter $parameter) => in_array($parameter->type, self::DEFAULT_PARAMETERS_TYPE)
                            )
                        )
                    )
                )
            );
    }

    private function resolveType(Parameter $parameter): string
    {
        return match ($parameter->type) {
            ParameterType::QUERY => UrlParameter::TYPE_QUERY,
            ParameterType::PATH => UrlParameter::TYPE_PATH,
            default => throw new DomainException(
                sprintf(
                    'Unavailable parameter type %s supported [%s]',
                    $parameter->type->value,
                    implode(',', self::DEFAULT_PARAMETERS_TYPE)
                )
            ),
        };
    }

    private function resolveArgumentIndex(ReflectionMethod $method, Parameter $parameter): int
    {
        foreach ($method->getParameters() as $methodParameter) {
            if ($methodParameter->getName() === $parameter->argumentName) {
                return $methodParameter->getPosition();
            }
        }

        throw new DomainException(
            sprintf(
                'Unexpected parameter %s in method %s',
                $parameter->argumentName,
                $method->getName()
            )
        );
    }
}