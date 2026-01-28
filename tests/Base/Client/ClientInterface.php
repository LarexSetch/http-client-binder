<?php

declare(strict_types=1);

namespace HttpClientBinder\Tests\Base\Client;

use HttpClientBinder\Annotation\Client;
use HttpClientBinder\Annotation\Header;
use HttpClientBinder\Annotation\Parameter;
use HttpClientBinder\Annotation\ParameterType;
use HttpClientBinder\Annotation\RequestMapping;
use HttpClientBinder\Tests\Base\Client\Dto\CreateDataRequest;
use HttpClientBinder\Tests\Base\Client\Dto\CreateDataResponse;
use HttpClientBinder\Tests\Base\Client\Dto\DataListResponse;
use HttpClientBinder\Tests\Base\Client\Dto\UpdateDataRequest;
use HttpClientBinder\Tests\Base\Client\Dto\UpdateDataResponse;

#[Client(
    baseUrl: "http://wiremock:8080",
    headers: [
        new Header("User-Agent", "Some-User-Agent/v1.0.5"),
    ]
)]
interface ClientInterface
{
    #[RequestMapping(
        "/api/v1/data",
        method: "GET",
        responseType: "application/json",
    )]
    public function getDataList(): DataListResponse;

    #[RequestMapping(
        "/api/v1/data",
        method: "POST",
        requestType: "application/json",
        responseType: "application/json",
    )]
    #[Parameter("data", type: ParameterType::BODY)]
    public function createData(CreateDataRequest $data): CreateDataResponse;

    #[Header("X-Request-Id", "update-data-{dataId}")]
    #[Parameter("data", type: ParameterType::BODY)]
    #[Parameter("id", "dataId", ParameterType::HEADER)]
    #[Parameter("id", "dataId", ParameterType::PATH)]
    #[RequestMapping(
        "/api/v1/data/{dataId}",
        method: "PUT",
        requestType: "application/json",
        responseType: "application/json",
    )]
    public function updateData(int $id, UpdateDataRequest $data): UpdateDataResponse;
}