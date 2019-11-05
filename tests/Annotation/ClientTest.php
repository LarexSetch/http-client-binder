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
    public function createClient(): void
    {
        $annotations = $this->readAnnotation();

        $this->assertEquals([
            new Client(['value' => 'http://test.com'])
        ], $annotations);
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