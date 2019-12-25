<?php

declare(strict_types=1);

namespace HttpClientBinder\Tests\Integration;

use HttpClientBinder\Tests\Base\Client\Dto\DataElement;
use HttpClientBinder\Tests\Base\Client\Dto\DataListResponse;
use HttpClientBinder\BinderBuilder;
use HttpClientBinder\Tests\Base\AbstractAnnotationTestCase;
use HttpClientBinder\Tests\Base\Client\ClientInterface;

final class ClientInterfaceDataListTest extends AbstractAnnotationTestCase
{
    /**
     * @test
     */
    public function getList(): void
    {
        /** @var ClientInterface $client */
        $client = BinderBuilder::builder()
            ->target(ClientInterface::class)
            ->getClient()
        ;

        $dataList = $client->getDataList();

        $this->assertInstanceOf(DataListResponse::class, $dataList);
        $this->assertEquals($this->createExpectedListResponse(), $dataList);
    }

    private function createExpectedListResponse(): DataListResponse
    {
        return
            (new DataListResponse())
                ->setElements([
                    (new DataElement())
                        ->setId(1)
                        ->setPropertyOne('one')
                        ->setPropertyTwo('two')
                ])
                ->setElementsCount(200)
                ->setElementsOnPage(1)
            ;
    }
}