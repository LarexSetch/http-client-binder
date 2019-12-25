<?php

declare(strict_types=1);

namespace HttpClientBinder\Tests\Unit;

use HttpClientBinder\Annotation\Client;
use HttpClientBinder\Annotation\RequestMapping;
use HttpClientBinder\BinderBuilder;
use HttpClientBinder\Tests\Base\AbstractAnnotationTestCase;
use Psr\Http\Message\StreamInterface;

final class BinderBuilderTest extends AbstractAnnotationTestCase
{
    /**
     * @test
     */
    public function build(): void
    {
        /** @var SomeMyClient $client */
        $client =
            BinderBuilder::builder()
                ->target(SomeMyClient::class)
                ->getClient();

        $this->assertInstanceOf(SomeMyClient::class, $client);
    }
}

/**
 * @Client(baseUrl="http://test.com")
 */
interface SomeMyClient
{
    /**
     * @RequestMapping("/some/information/{id}", method="POST")
     */
    public function getSomeInformation(int $id): StreamInterface;
}