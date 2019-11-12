<?php

declare(strict_types=1);

namespace HttpClientBinder\Tests\Base;

use Doctrine\Common\Annotations\AnnotationRegistry;
use HttpClientBinder\Annotation\Client;
use HttpClientBinder\Annotation\Header;
use HttpClientBinder\Annotation\HeaderBag;
use HttpClientBinder\Annotation\Parameter;
use HttpClientBinder\Annotation\ParameterBag;
use HttpClientBinder\Annotation\RequestMapping;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

abstract class AbstractAnnotationTestCase extends TestCase
{
    protected function setUp(): void
    {
        $this->registerAnnotation(Client::class);
        $this->registerAnnotation(Header::class);
        $this->registerAnnotation(HeaderBag::class);
        $this->registerAnnotation(Parameter::class);
        $this->registerAnnotation(ParameterBag::class);
        $this->registerAnnotation(RequestMapping::class);
        $this->registerAnnotation(Type::class);
        $this->registerAnnotation(SerializedName::class);
    }

    /**
     * @throws mixed
     */
    protected function registerAnnotation(string $className): void
    {
        $annotationReflection = new ReflectionClass($className);
        AnnotationRegistry::registerFile($annotationReflection->getFileName());
    }
}