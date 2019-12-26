<?php

declare(strict_types=1);

namespace HttpClientBinder\Tests\Unit\Mapping;

use HttpClientBinder\Mapping\Dto\HttpHeaderParameter;
use HttpClientBinder\Tests\Base\Client\Dto\CreateDataRequest;
use HttpClientBinder\Tests\Base\Client\Dto\CreateDataResponse;
use HttpClientBinder\Tests\Base\Client\Dto\DataListResponse;
use HttpClientBinder\Tests\Base\Client\Dto\UpdateDataRequest;
use HttpClientBinder\Tests\Base\Client\Dto\UpdateDataResponse;
use Doctrine\Common\Annotations\AnnotationReader;
use HttpClientBinder\Mapping\Dto\Endpoint;
use HttpClientBinder\Mapping\Dto\EndpointBag;
use HttpClientBinder\Mapping\Dto\HttpHeader;
use HttpClientBinder\Mapping\Dto\HttpHeaderBag;
use HttpClientBinder\Mapping\Dto\MappingClient;
use HttpClientBinder\Mapping\Dto\RequestType;
use HttpClientBinder\Mapping\Dto\Url;
use HttpClientBinder\Mapping\Dto\UrlParameter;
use HttpClientBinder\Mapping\Dto\UrlParameterBag;
use HttpClientBinder\Mapping\Extractor\HeadersExtractor;
use HttpClientBinder\Mapping\Extractor\RequestTypeExtractor;
use HttpClientBinder\Mapping\Extractor\UrlParametersExtractor;
use HttpClientBinder\Mapping\MapFromAnnotation;
use HttpClientBinder\Mapping\MappingBuilderInterface;
use HttpClientBinder\Tests\Base\AbstractAnnotationTestCase;
use HttpClientBinder\Tests\Base\Client\ClientInterface;
use ReflectionClass;

final class MapFromAnnotationTest extends AbstractAnnotationTestCase
{
    /**
     * @test
     */
    public function build(): void
    {
        $builder = $this->createBuilder();

        $clientMapping = $builder->build();

        $this->assertEquals($this->createExpectedMapping(), $clientMapping);
    }

    /**
     * @throws mixed
     */
    private function createBuilder(): MappingBuilderInterface
    {
        $reader = new AnnotationReader();

        return
            new MapFromAnnotation(
                new ReflectionClass(ClientInterface::class),
                $reader,
                new UrlParametersExtractor($reader),
                new HeadersExtractor($reader),
                new RequestTypeExtractor($reader)
            );
    }

    private function createExpectedMapping(): MappingClient
    {
        return
            new MappingClient(
                new EndpointBag([
                    new Endpoint(
                        'getDataList',
                        'GET',
                        DataListResponse::class,
                        new Url('/api/v1/data', new UrlParameterBag([])),
                        new HttpHeaderBag([]),
                        null
                    ),
                    new Endpoint(
                        'createData',
                        'POST',
                        CreateDataResponse::class,
                        new Url('/api/v1/data', new UrlParameterBag([])),
                        new HttpHeaderBag([
                            new HttpHeader('Content-type', ['application/json']),
                        ]),
                        new RequestType('data', 0, CreateDataRequest::class)
                    ),
                    new Endpoint(
                        'updateData',
                        'PUT',
                        UpdateDataResponse::class,
                        new Url('/api/v1/data/{dataId}', new UrlParameterBag([
                            new UrlParameter('id', 0, UrlParameter::TYPE_PATH, 'dataId')
                        ])),
                        new HttpHeaderBag([
                            new HttpHeader('Content-type', ['application/json']),
                            new HttpHeader('X-Request-Id', ['update-data-{dataId}'], [
                                new HttpHeaderParameter('id', 0, 'dataId')
                            ]),
                        ]),
                        new RequestType('data', 1, UpdateDataRequest::class)
                    ),
                ])
            );
    }
}
