<?php

namespace HttpClientBinder\Mapping\Extractor;

use Doctrine\Common\Annotations\Reader;
use HttpClientBinder\Annotation\Parameter;
use HttpClientBinder\Annotation\ParameterBag;
use HttpClientBinder\Mapping\Dto\UrlParameter;
use HttpClientBinder\Mapping\Dto\UrlParameterBag;
use ReflectionMethod;
use Exception;

final class UrlParametersExtractor implements UrlParametersExtractorInterface
{
    /**
     * @var Reader
     */
    private $annotationReader;

    public function __construct(Reader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    public function extract(ReflectionMethod $method): UrlParameterBag
    {
        $parameterBag = $this->annotationReader->getMethodAnnotation($method, ParameterBag::class);
        if (null === $parameterBag) {
            return new UrlParameterBag([]);
        }

        return
            new UrlParameterBag(
                array_values(
                    array_map(
                        function (Parameter $parameter) {
                            return
                                new UrlParameter(
                                    $parameter->getArgumentName(),
                                    $this->resolveType($parameter),
                                    $parameter->getAlias()
                                );
                        },
                        array_filter(
                            $parameterBag->getParameters(),
                            function (Parameter $parameter) {
                                return in_array($parameter->getType(), [Parameter::TYPE_PATH, Parameter::TYPE_QUERY]);
                            }
                        )
                    )
                )
            );
    }

    private function resolveType(Parameter $parameter): string
    {
        switch ($parameter->getType()) {
            case Parameter::TYPE_QUERY:
                return UrlParameter::TYPE_QUERY;
            case Parameter::TYPE_PATH:
                return UrlParameter::TYPE_PATH;
            default:
                throw new Exception('Unavailable parameter');
        }
    }
}