<?php

declare(strict_types=1);

namespace HttpClientBinder\Fabrics\Mapping;

use Doctrine\Common\Annotations\AnnotationReader;
use HttpClientBinder\Mapping\Extractor\HeadersExtractor;
use HttpClientBinder\Mapping\Extractor\RequestTypeExtractor;
use HttpClientBinder\Mapping\Extractor\UrlParametersExtractor;
use HttpClientBinder\Mapping\MapFromAnnotation;
use HttpClientBinder\Mapping\MappingBuilderInterface;
use ReflectionClass;

final class MapFromAnnotationFactory implements MappingBuilderFactoryInterface
{
    public function create(string $className): MappingBuilderInterface
    {
        $reflectionClass = new ReflectionClass($className);
        $reader = new AnnotationReader();

        return
            new MapFromAnnotation(
                $reflectionClass,
                $reader,
                new UrlParametersExtractor($reader),
                new HeadersExtractor($reader),
                new RequestTypeExtractor($reader)
            );
    }
}