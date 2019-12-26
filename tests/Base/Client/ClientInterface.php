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
use HttpClientBinder\Annotation\RequestMapping;

/**
 * @Client(
 *     headers={
 *          @Header("User-Agent", values="Some-User-Agent/v1.0.5")
 *     }
 * )
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
     * @ParameterBag({
     *     @Parameter("data", types=Parameter::TYPE_BODY)
     * })
     * @RequestMapping(
     *     "/api/v1/data",
     *     method="POST",
     *     requestType="application/json",
     *     responseType="application/json"
     * )
     */
    public function createData(CreateDataRequest $data): CreateDataResponse;

    /**
     * @HeaderBag({
     *     @Header("X-Request-Id", values="update-data-{dataId}")
     * })
     * @ParameterBag({
     *     @Parameter("id", alias="dataId", types={Parameter::TYPE_PATH, Parameter::TYPE_HEADER}),
     *     @Parameter("data", types=Parameter::TYPE_BODY)
     * })
     * @RequestMapping(
     *     "/api/v1/data/{dataId}",
     *     method="PUT",
     *     requestType="application/json",
     *     responseType="application/json"
     * )
     */
    public function updateData(int $id, UpdateDataRequest $data): UpdateDataResponse;
}