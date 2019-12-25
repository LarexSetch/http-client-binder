<?php

declare(strict_types=1);

namespace HttpClientBinder\Tests\Base\Client;

use HttpClientBinder\Tests\Base\Client\Dto\CreateDataRequest;
use HttpClientBinder\Tests\Base\Client\Dto\CreateDataResponse;
use HttpClientBinder\Tests\Base\Client\Dto\DataListResponse;
use HttpClientBinder\Tests\Base\Client\Dto\UpdateDataRequest;
use HttpClientBinder\Tests\Base\Client\Dto\UpdateDataResponse;
use HttpClientBinder\Annotation\Client;
use HttpClientBinder\Annotation\Header;
use HttpClientBinder\Annotation\HeaderBag;
use HttpClientBinder\Annotation\Parameter;
use HttpClientBinder\Annotation\ParameterBag;
use HttpClientBinder\Annotation\RequestBody;
use HttpClientBinder\Annotation\RequestMapping;

/**
 * @Client("http://wiremock:8080")
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
    public function getDataList(): DataListResponse;

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