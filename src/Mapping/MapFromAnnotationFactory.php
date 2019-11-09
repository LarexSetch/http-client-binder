<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping;

use Doctrine\Common\Annotations\AnnotationReader;
use HttpClientBinder\Method\ReflectionMethodsProvider;
use ReflectionClass;

final class MapFromAnnotationFactory implements MappingBuilderFactoryInterface
{
    public function create(string $className): MappingBuilderInterface
    {
        $reflectionClass = new ReflectionClass($className);

        return
            new MapFromAnnotation(
                $reflectionClass,
                new ReflectionMethodsProvider($reflectionClass),
                new AnnotationReader()
            );
    }
}