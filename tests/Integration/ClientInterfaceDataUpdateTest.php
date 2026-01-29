<?php

declare(strict_types=1);

namespace HttpClientBinder\Tests\Integration;

use HttpClientBinder\Fabrics\BinderBuilder;
use HttpClientBinder\Tests\Base\AbstractAnnotationTestCase;
use HttpClientBinder\Tests\Base\Client\ClientInterface;
use HttpClientBinder\Tests\Base\Client\Dto\UpdateDataRequest;
use HttpClientBinder\Tests\Base\Client\Dto\UpdateDataResponse;

final class ClientInterfaceDataUpdateTest extends AbstractAnnotationTestCase
{
    public function test_update(): void
    {
        /** @var ClientInterface $client */
        $client = BinderBuilder::builder(ClientInterface::class, WIREMOCK_HOST)
            ->temporaryDirectory(TMP_DIR)
            ->getClient();

        $dataList = $client->updateData(10030, $this->createRequest());

        $this->assertInstanceOf(UpdateDataResponse::class, $dataList);
        $this->assertEquals($this->createExpectedListResponse(), $dataList);
    }

    private function createRequest(): UpdateDataRequest
    {
        return (new UpdateDataRequest('Some value of one property', 'The two value'));
    }

    private function createExpectedListResponse(): UpdateDataResponse
    {
        return (new UpdateDataResponse(10030, 'Some value of one property', 'The two value'));
    }
}