<?php

declare(strict_types=1);

namespace HttpClientBinder\Tests\Integration;

use HttpClientBinder\Fabrics\BinderBuilder;
use HttpClientBinder\Tests\Base\AbstractAnnotationTestCase;
use HttpClientBinder\Tests\Base\Client\ClientInterface;
use HttpClientBinder\Tests\Base\Client\Dto\DataElement;
use HttpClientBinder\Tests\Base\Client\Dto\DataListResponse;

final class ClientInterfaceDataListTest extends AbstractAnnotationTestCase
{
    public function test_get_list(): void
    {
        /** @var ClientInterface $client */
        $client = BinderBuilder::builder(ClientInterface::class, WIREMOCK_HOST)
            ->temporaryDirectory(TMP_DIR)
            ->getClient();

        $dataList = $client->getDataList();

        $this->assertInstanceOf(DataListResponse::class, $dataList);
        $this->assertEquals($this->createExpectedListResponse(), $dataList);
    }

    private function createExpectedListResponse(): DataListResponse
    {
        return
            (new DataListResponse(
                [(new DataElement(1, 'one', 'two'))],
                200,
                1
            ));
    }
}