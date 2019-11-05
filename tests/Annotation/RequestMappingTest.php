<?php

declare(strict_types=1);

namespace Annotation;

use Doctrine\Common\Annotations\AnnotationReader;
use HttpClientBinder\Annotation\RequestMapping;
use HttpClientBinder\Tests\Base\AbstractAnnotationTestCase;
use ReflectionClass;

final class RequestMappingTest extends AbstractAnnotationTestCase
{
    /**
     * @test
     */
    public function createClient(): void
    {
        $annotations = $this->readAnnotation();

        $this->assertEquals([
            new RequestMapping([
                'value' => '/api/v1/some/data',
                'method' => 'POST',
                'requestType' => 'application/json',
                'responseType' => 'application/xml'
            ])
        ], $annotations);
    }

    private function readAnnotation(): array
    {
        $reflectionClass = new ReflectionClass(ClientWithRequestLine::class);
        $reflectionMethod = $reflectionClass->getMethod('getData');

        $reader = new AnnotationReader();

        return $reader->getMethodAnnotations($reflectionMethod);
    }
}

interface ClientWithRequestLine
{
    /**
     * @RequestMapping(
     *     "/api/v1/some/data",
     *     method="POST",
     *     requestType="application/json",
     *     responseType="application/xml"
     * )
     */
    public function getData(int $id);
}