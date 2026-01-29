<?php

declare(strict_types=1);

namespace HttpClientBinder\Tests\Unit\Proxy;

use HttpClientBinder\Enums\HttpMethod;
use HttpClientBinder\Metadata\Dto\Endpoint;
use HttpClientBinder\Metadata\Dto\EndpointArgument;
use HttpClientBinder\Metadata\Dto\HttpHeader;
use HttpClientBinder\Metadata\Dto\Route;
use HttpClientBinder\Proxy\EndpointRenderer;
use PHPUnit\Framework\TestCase;

class EndpointRendererTest extends TestCase
{
    public function testRender(): void
    {
        $arg1 = new EndpointArgument(0, 'id', 'int');
        $arg2 = new EndpointArgument(1, 'data', 'array');

        $endpoint = new Endpoint(
            route: new Route(
                method: HttpMethod::POST,
                pathPattern: '/users/{id}',
                parameters: ['id' => $arg1]
            ),
            name: 'createUser',
            headers: [
                new HttpHeader('X-Custom', 'Value'),
                new HttpHeader('Authorization', '{token}', ['{token}' => new EndpointArgument(2, 'token', 'string')])
            ],
            arguments: [$arg1, $arg2],
            resultType: 'void',
            requestBody: $arg2
        );

        $code = EndpointRenderer::render($endpoint);

        $this->assertStringContainsString('new \HttpClientBinder\Metadata\Dto\Endpoint', $code);
        $this->assertStringContainsString("'createUser'", $code);
        $this->assertStringContainsString('HttpMethod::POST', $code);
        $this->assertStringContainsString("'/users/{id}'", $code);
        $this->assertStringContainsString("'id' => new \HttpClientBinder\Metadata\Dto\EndpointArgument(position: 0, name: 'id', type: 'int')", $code);
        $this->assertStringContainsString("'X-Custom'", $code);
        $this->assertStringContainsString("'void'", $code);

        // Попробуем выполнить этот код
        $evalResult = eval("return " . $code . ";");
        $this->assertInstanceOf(Endpoint::class, $evalResult);
        $this->assertEquals($endpoint->name, $evalResult->name);
        $this->assertEquals($endpoint->route->method, $evalResult->route->method);
        $this->assertEquals($endpoint->route->pathPattern, $evalResult->route->pathPattern);
        $this->assertEquals($endpoint->resultType, $evalResult->resultType);
        $this->assertCount(count($endpoint->headers), $evalResult->headers);
    }
}
