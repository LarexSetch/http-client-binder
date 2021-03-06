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
    /**
     * @test
     */
    public function update(): void
    {
        /** @var ClientInterface $client */
        $client = BinderBuilder::builder()
            ->target(ClientInterface::class, WIREMOCK_HOST)
            ->getClient()
        ;

        $dataList = $client->updateData(10030, $this->createRequest());

        $this->assertInstanceOf(UpdateDataResponse::class, $dataList);
        $this->assertEquals($this->createExpectedListResponse(), $dataList);
    }

    private function createRequest(): UpdateDataRequest
    {
        return
            (new UpdateDataRequest())
                ->setPropertyOne("Some value of one property")
                ->setPropertyTwo("The two value")
            ;
    }

    private function createExpectedListResponse(): UpdateDataResponse
    {
        return
            (new UpdateDataResponse())
                ->setId(10030)
                ->setPropertyOne('Some value of one property')
                ->setPropertyTwo('The two value')
            ;
    }
}