<?php

declare(strict_types=1);

namespace HttpClientBinder\Tests\Annotation;

use Doctrine\Common\Annotations\AnnotationReader;
use HttpClientBinder\Annotation\Header;
use HttpClientBinder\Annotation\HeaderBag;
use HttpClientBinder\Tests\Base\AbstractAnnotationTestCase;
use ReflectionClass;

final class HeaderBagTest extends AbstractAnnotationTestCase
{
    /**
     * @test
     */
    public function createClient(): void
    {
        $annotations = $this->readAnnotation();

        $this->assertEquals([
            new HeaderBag(['value' => [new Header(['value' => 'Content-type', 'values' => 'application/json'])]])
        ], $annotations);
    }

    private function readAnnotation(): array
    {
        $reflectionClass = new ReflectionClass(ClientWithHeader::class);

        $reader = new AnnotationReader();

        return $reader->getClassAnnotations($reflectionClass);
    }
}

/**
 * @HeaderBag({
 *  @Header("Content-type", values={"application/json"})
 * })
 */
interface ClientWithHeader
{
}