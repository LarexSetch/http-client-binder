<?php

namespace HttpClientBinder\Tests\Mapping;

use Doctrine\Common\Annotations\AnnotationReader;
use HttpClientBinder\Annotation\Client;
use HttpClientBinder\Annotation\Header;
use HttpClientBinder\Annotation\HeaderBag;
use HttpClientBinder\Annotation\Parameter;
use HttpClientBinder\Annotation\ParameterBag;
use HttpClientBinder\Annotation\RequestBody;
use HttpClientBinder\Annotation\RequestMapping;
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
                'http://example.com',
                new EndpointBag([
                    new Endpoint(
                        'getDataList',
                        'GET',
                        DataList::class,
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
                        new RequestType('data', CreateDataRequest::class)
                    ),
                    new Endpoint(
                        'updateData',
                        'PUT',
                        UpdateDataResponse::class,
                        new Url('/api/v1/data/{dataId}', new UrlParameterBag([
                            new UrlParameter('id', UrlParameter::TYPE_PATH, 'dataId')
                        ])),
                        new HttpHeaderBag([
                            new HttpHeader('Content-type', ['application/json']),
                            new HttpHeader('X-Request-Id', ['update-data-{entityId}']),
                        ]),
                        new RequestType('data', UpdateDataRequest::class)
                    ),
                ])
            );
    }
}

/**
 * @Client("http://example.com")
 */
interface ClientInterface
{
    /**
     * @RequestMapping(
     *     "/api/v1/data",
     *     method="GET",
     *     responseType="application/json"
     * )
     */
    public function getDataList(): DataList;

    /**
     * @RequestMapping(
     *     "/api/v1/data",
     *     method="POST",
     *     requestType="application/json",
     *     responseType="application/json"
     * )
     * @RequestBody("data")
     */
    public function createData(CreateDataRequest $data): CreateDataResponse;

    /**
     * @HeaderBag({
     *     @Header("X-Request-Id", values="update-data-{entityId}")
     * })
     * @ParameterBag({
     *     @Parameter("id", alias="entityId", type=Parameter::TYPE_HEADER),
     *     @Parameter("id", alias="dataId", type=Parameter::TYPE_PATH)
     * })
     * @RequestMapping(
     *     "/api/v1/data/{dataId}",
     *     method="PUT",
     *     requestType="application/json",
     *     responseType="application/json"
     * )
     * @RequestBody(argumentName="data")
     */
    public function updateData(int $id, UpdateDataRequest $data): UpdateDataResponse;
}

class DataList
{
}

class CreateDataRequest
{
}

class CreateDataResponse
{
}

class UpdateDataRequest
{
}

class UpdateDataResponse
{
}