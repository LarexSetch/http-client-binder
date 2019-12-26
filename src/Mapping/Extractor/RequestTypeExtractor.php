<?php

namespace HttpClientBinder\Mapping\Extractor;

use Doctrine\Common\Annotations\Reader;
use HttpClientBinder\Annotation\RequestBody;
use HttpClientBinder\Mapping\Dto\RequestType;
use ReflectionMethod;
use ReflectionParameter;
use DomainException;

final class RequestTypeExtractor implements RequestTypeExtractorInterface
{
    /**
     * @var Reader
     */
    private $annotationReader;

    public function __construct(Reader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    public function extract(ReflectionMethod $method): ?RequestType
    {
        /** @var RequestBody $requestBody */
        $requestBody = $this->annotationReader->getMethodAnnotation($method, RequestBody::class);

        if (null === $requestBody) {
            return null;
        }

        /** @var \ReflectionParameter $parameter */
        foreach ($method->getParameters() as $parameter) {
            if ($parameter->getName() === $requestBody->getArgumentName()) {
                return $this->createRequestType($parameter);
            }
        }

        return null;
    }

    private function createRequestType(ReflectionParameter $parameter): RequestType
    {
        $type = $parameter->getType();
        if (null === $type) {
            throw new DomainException(sprintf("You must define argument type for %s", $parameter->getName()));
        }

        return
            new RequestType(
                $parameter->getName(),
                $parameter->getPosition(),
                $type->getName()
            );
    }
}