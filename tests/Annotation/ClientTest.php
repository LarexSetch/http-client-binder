<?php

declare(strict_types=1);

namespace HttpClientBinder\Tests\Annotation;

use Doctrine\Common\Annotations\AnnotationReader;
use HttpClientBinder\Annotation\Client;
use HttpClientBinder\Tests\Base\AbstractAnnotationTestCase;

final class ClientTest extends AbstractAnnotationTestCase
{
    /**
     * @test
     */
    public function createFromInterface(): void
    {
        $annotations = $this->readAnnotation();
        $expectedAnnotations = [
            new Client(['value' => 'http://test.com'])
        ];

        $this->assertEquals($expectedAnnotations, $annotations);
    }

    private function readAnnotation(): array
    {
        $reflectionClass = new \ReflectionClass(ClientInterface::class);

        $reader = new AnnotationReader();
        return $reader->getClassAnnotations($reflectionClass);
    }
}

/**
 * @Client("http://test.com")
 */
interface ClientInterface {

}