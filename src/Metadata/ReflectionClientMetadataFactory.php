<?php

declare(strict_types=1);

namespace HttpClientBinder\Metadata;

use DomainException;
use HttpClientBinder\Annotation\Client;
use HttpClientBinder\Annotation\Header;
use HttpClientBinder\Metadata\Dto\ClientMetadata;
use HttpClientBinder\Metadata\Dto\Endpoint;
use HttpClientBinder\Metadata\Dto\HttpHeader;
use HttpClientBinder\Metadata\Dto\Route;
use ReflectionClass;
use ReflectionMethod;

readonly final class ReflectionClientMetadataFactory implements ClientMetadataFactory
{
    public function create(string $interfaceName, string $baseUrl): ClientMetadata
    {
        $reflectionClass = new ReflectionClass($interfaceName);
        $client = $this->getClientAnnotation($reflectionClass);

        return
            new ClientMetadata(
                $interfaceName,
                array_map(
                    fn($reflectionMethod) => EndpointFactory::create($reflectionMethod),
                    $reflectionClass->getMethods()
                ),
                array_map(
                    fn(Header $header) => new HttpHeader($header->header, $header->getValue()),
                    $client->headers
                ),
                $baseUrl
            );
    }

    private function getClientAnnotation(ReflectionClass $reflectionClass): Client
    {
        $reflectionAttribute = $reflectionClass->getAttributes(Client::class)[0] ?? null;
        if (null === $reflectionAttribute) {
            throw new DomainException(
                sprintf('You must define the #[%s] in %s', Client::class, $reflectionClass->getName())
            );
        }

        /** @var Client $client */
        $client = $reflectionAttribute->newInstance();

        return $client;
    }

}