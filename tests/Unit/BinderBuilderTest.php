<?php

declare(strict_types=1);

namespace HttpClientBinder\Tests\Unit;

use HttpClientBinder\Annotation\Client;
use HttpClientBinder\Annotation\RequestMapping;
use HttpClientBinder\Enums\HttpMethod;
use HttpClientBinder\Fabrics\BinderBuilder;
use HttpClientBinder\Tests\Base\AbstractAnnotationTestCase;
use Psr\Http\Message\StreamInterface;

final class BinderBuilderTest extends AbstractAnnotationTestCase
{
    public function test_build(): void
    {
        /** @var SomeMyClient $client */
        $client = BinderBuilder::builder(SomeMyClient::class, 'https://test.com')
            ->temporaryDirectory(TMP_DIR)
            ->getClient();

        $this->assertInstanceOf(SomeMyClient::class, $client);
    }
}

#[Client]
interface SomeMyClient
{
    #[RequestMapping("/some/information/{id}", method: HttpMethod::POST)]
    public function getSomeInformation(int $id): StreamInterface;
}