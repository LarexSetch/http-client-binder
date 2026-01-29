<?php

declare(strict_types=1);

namespace HttpClientBinder\Tests\Base\Client;

use HttpClientBinder\Annotation\Client;
use HttpClientBinder\Annotation\Header;
use HttpClientBinder\Annotation\HeaderParameter;
use HttpClientBinder\Annotation\PathParameter;
use HttpClientBinder\Annotation\RequestBody;
use HttpClientBinder\Annotation\RequestMapping;
use HttpClientBinder\Enums\HttpMethod;
use HttpClientBinder\Tests\Base\Client\Dto\CreateDataRequest;
use HttpClientBinder\Tests\Base\Client\Dto\CreateDataResponse;
use HttpClientBinder\Tests\Base\Client\Dto\DataListResponse;
use HttpClientBinder\Tests\Base\Client\Dto\UpdateDataRequest;
use HttpClientBinder\Tests\Base\Client\Dto\UpdateDataResponse;

#[Client(
    headers: [
        new Header("User-Agent", "Some-User-Agent/v1.0.5"),
    ]
)]
interface ClientInterface
{
    #[RequestMapping(
        "/api/v1/data",
        method: HttpMethod::GET,
        responseType: "application/json",
    )]
    public function getDataList(): DataListResponse;

    #[RequestMapping(
        "/api/v1/data",
        method: HttpMethod::POST,
        requestType: "application/json",
        responseType: "application/json",
    )]
    public function createData(
        #[RequestBody("application/json")]
        CreateDataRequest $data
    ): CreateDataResponse;

    #[RequestMapping(
        "/api/v1/data/{dataId}",
        method: HttpMethod::PUT,
        responseType: "application/json",
    )]
    public function updateData(
        #[HeaderParameter("X-Request-Id", pattern: 'update-data-{id}')]
        #[PathParameter("{dataId}")]
        int $id,
        #[RequestBody("application/json")]
        UpdateDataRequest $data
    ): UpdateDataResponse;
}