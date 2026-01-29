<?php

declare(strict_types=1);

namespace HttpClientBinder\Tests\Unit\Mapping;

use HttpClientBinder\Enums\HttpMethod;
use HttpClientBinder\Metadata\Dto\Endpoint;
use HttpClientBinder\Metadata\Dto\EndpointArgument;
use HttpClientBinder\Metadata\Dto\HttpHeader;
use HttpClientBinder\Metadata\Dto\Route;
use HttpClientBinder\Metadata\EndpointFactory;
use HttpClientBinder\Tests\Base\AbstractAnnotationTestCase;
use HttpClientBinder\Tests\Base\Client\ClientInterface;
use HttpClientBinder\Tests\Base\Client\Dto\UpdateDataRequest;
use HttpClientBinder\Tests\Base\Client\Dto\UpdateDataResponse;
use ReflectionMethod;

class MetadataExtractorTest extends AbstractAnnotationTestCase
{
    public function test_extract_data()
    {
        $reflectionMethod = new ReflectionMethod(ClientInterface::class, 'updateData');
        $argument0 = new EndpointArgument(0, 'id', 'int');
        $argument1 = new EndpointArgument(1, 'data', UpdateDataRequest::class);

        $endpoint = EndpointFactory::create($reflectionMethod);

        $this->assertEquals(
            new Endpoint(
                route: new Route(
                    HttpMethod::PUT,
                    '/api/v1/data/{dataId}',
                    ['{dataId}' => $argument0]
                ),
                name: 'updateData',
                headers: [
                    new HttpHeader('Accept', 'application/json'),
                    new HttpHeader('X-Request-Id', 'update-data-{id}', ['{id}' => $argument0]),
                    new HttpHeader('Content-Type', 'application/json'),
                ],
                arguments: [
                    $argument0,
                    $argument1,
                ],
                resultType: UpdateDataResponse::class,
                requestBody: $argument1,
            ),
            $endpoint
        );
    }
}
