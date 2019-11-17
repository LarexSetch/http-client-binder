<?php

declare(strict_types=1);

namespace HttpClientBinder\Tests\Base;

use HttpClientBinder\Annotation\Client;
use HttpClientBinder\Annotation\RequestMapping;
use HttpClientBinder\Mapping\MapFromAnnotationFactory;
use HttpClientBinder\Method\ReflectionMethodsProviderFactory;
use HttpClientBinder\Proxy\ProxyFactory;
use HttpClientBinder\Proxy\ProxyFactoryInterface;
use HttpClientBinder\Proxy\ProxySourceRender;
use HttpClientBinder\Proxy\ProxySourceStorage;
use JMS\Serializer\SerializerBuilder;
use Psr\Http\Message\StreamInterface;

final class ProxyFactoryIntegrationTest extends AbstractAnnotationTestCase
{
    /**
     * @test
     */
    public function build(): void
    {
        $proxyFactory = $this->createProxyFactory();

        /** @var SomeMyClient $client */
        $client = $proxyFactory->build(SomeMyClient::class);

        $this->assertInstanceOf(SomeMyClient::class, $client);
        $this->assertInstanceOf(StreamInterface::class, $client->getHome());
    }

    private function createProxyFactory(): ProxyFactoryInterface
    {
        return
            new ProxyFactory(
                new MapFromAnnotationFactory(),
                SerializerBuilder::create()->build(),
                new ProxySourceRender(),
                new ProxySourceStorage(TMP_DIR),
                new ReflectionMethodsProviderFactory()
            );
    }
}

/**
 * @Client(baseUrl="http://test.com")
 */
interface SomeMyClient {
    /**
     * @RequestMapping("/", method="GET")
     */
    public function getHome(): StreamInterface;
}