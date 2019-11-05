<?php

declare(strict_types=1);

namespace HttpClientBinder\Tests\Annotation;

use Doctrine\Common\Annotations\AnnotationReader;
use HttpClientBinder\Annotation\Header;
use HttpClientBinder\Annotation\HeaderBag;
use HttpClientBinder\Annotation\Parameter;
use HttpClientBinder\Annotation\ParameterBag;
use HttpClientBinder\Tests\Base\AbstractAnnotationTestCase;
use ReflectionClass;

final class ParameterBagTest extends AbstractAnnotationTestCase
{
    /**
     * @test
     */
    public function createClient(): void
    {
        $annotations = $this->readAnnotation();

        $this->assertEquals([
            new ParameterBag(['value' => [new Parameter(['propertyName' => 'id', 'alias' => 'dataId'])]])
        ], $annotations);
    }

    private function readAnnotation(): array
    {
        $reflectionClass = new ReflectionClass(ClientWithGetParameters::class);
        $reflectionMethod = $reflectionClass->getMethod('getData');

        $reader = new AnnotationReader();

        return $reader->getMethodAnnotations($reflectionMethod);
    }
}

interface ClientWithGetParameters
{
    /**
     * @ParameterBag({
     *     @Parameter("id", alias = "dataId")
     * })
     */
    public function getData(int $id);
}