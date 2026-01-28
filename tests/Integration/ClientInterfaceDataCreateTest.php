<?php

declare(strict_types=1);

namespace HttpClientBinder\Tests\Integration;

use HttpClientBinder\Fabrics\BinderBuilder;
use HttpClientBinder\Tests\Base\AbstractAnnotationTestCase;
use HttpClientBinder\Tests\Base\Client\ClientInterface;
use HttpClientBinder\Tests\Base\Client\Dto\CreateDataRequest;
use HttpClientBinder\Tests\Base\Client\Dto\CreateDataResponse;

final class ClientInterfaceDataCreateTest extends AbstractAnnotationTestCase
{
    public function test_create(): void
    {
        /** @var ClientInterface $client */
        $client = BinderBuilder::builder(ClientInterface::class, WIREMOCK_HOST)
            ->getClient();

        $dataList = $client->createData($this->createRequest());

        $this->assertInstanceOf(CreateDataResponse::class, $dataList);
        $this->assertEquals($this->createExpectedResponse(), $dataList);
    }

    private function createRequest(): CreateDataRequest
    {
        return (new CreateDataRequest("one", "two"));
    }

    private function createExpectedResponse(): CreateDataResponse
    {
        return (new CreateDataResponse(3298, 'one', 'two'));
    }
}