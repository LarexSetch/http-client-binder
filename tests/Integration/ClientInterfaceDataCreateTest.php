<?php

declare(strict_types=1);

namespace HttpClientBinder\Tests\Integration;

use HttpClientBinder\BinderBuilder;
use HttpClientBinder\Tests\Base\AbstractAnnotationTestCase;
use HttpClientBinder\Tests\Base\Client\ClientInterface;
use HttpClientBinder\Tests\Base\Client\Dto\CreateDataRequest;
use HttpClientBinder\Tests\Base\Client\Dto\CreateDataResponse;

final class ClientInterfaceDataCreateTest extends AbstractAnnotationTestCase
{
    /**
     * @test
     */
    public function create(): void
    {
        /** @var ClientInterface $client */
        $client = BinderBuilder::builder()
            ->target(ClientInterface::class)
            ->getClient()
        ;

        $dataList = $client->createData($this->createRequest());

        $this->assertInstanceOf(CreateDataResponse::class, $dataList);
        $this->assertEquals($this->createExpectedResponse(), $dataList);
    }

    private function createRequest(): CreateDataRequest
    {
        return
            (new CreateDataRequest())
                ->setPropertyOne("one")
                ->setPropertyTwo("two")
            ;
    }

    private function createExpectedResponse(): CreateDataResponse
    {
        return
            (new CreateDataResponse())
                ->setId(3298)
                ->setPropertyOne('one')
                ->setPropertyTwo('two')
            ;
    }
}