<?php

declare(strict_types=1);

namespace Provider;

use Doctrine\Common\Annotations\AnnotationReader;
use HttpClientBinder\Annotation\HeaderBag;
use HttpClientBinder\Annotation\Header;
use HttpClientBinder\Annotation\ParameterBag;
use HttpClientBinder\Annotation\Parameter;
use HttpClientBinder\Annotation\RequestMapping;
use HttpClientBinder\Annotation\RequestBody;
use HttpClientBinder\Provider\Dto\Argument;
use HttpClientBinder\Provider\Dto\Method;
use HttpClientBinder\Provider\ReflectionMethodsProvider;
use HttpClientBinder\Tests\Base\AbstractAnnotationTestCase;
use ReflectionClass;

final class ReflectionMethodsProviderTest extends AbstractAnnotationTestCase
{
    /**
     * @test
     * @dataProvider provideDataProvider
     */
    public function provide(string $className, array $expectedResult): void
    {
        $provider = new ReflectionMethodsProvider(new ReflectionClass($className), new AnnotationReader());
        $this->assertEquals($expectedResult, $provider->provide());
    }

    public function provideDataProvider(): iterable
    {
        yield [
            FirstInterface::class,
            [
                new Method(
                    'first',
                    new RequestMapping([
                        'value' => '/some/first',
                        'method' => 'GET',
                        'requestType' => 'application/json',
                        'responseType' => 'application/xml'
                    ]),
                    null,
                    null,
                    null,
                    'array',
                    []
                ),
            ]
        ];

        yield [
            SecondInterface::class,
            [
                new Method(
                    'second',
                    new RequestMapping([
                        'value' => '/some/second',
                        'method' => 'GET',
                    ]),
                    new HeaderBag([
                        'value' => [new Header(['value' => 'Content-type: application/json'])]
                    ]),
                    null,
                    null,
                    'string',
                    []
                ),
            ]
        ];

        yield [
            ThirdInterface::class,
            [
                new Method(
                    'third',
                    new RequestMapping([
                        'value' => '/some/third',
                        'method' => 'GET',
                    ]),
                    null,
                    new ParameterBag([
                        'value' => [new Parameter(['argumentName' => 'id', 'alias' => 'thirdId'])]
                    ]),
                    null,
                    'array',
                    [new Argument('id', 'int')]
                ),
            ]
        ];

        yield [
            FourthInterface::class,
            [
                new Method(
                    'fourth',
                    new RequestMapping([
                        'value' => '/some/fourth',
                        'method' => 'POST',
                    ]),
                    null,
                    null,
                    new RequestBody(['argumentName' => 'theBody']),
                    SomeResponseBody::class,
                    [new Argument('theBody', SomeRequestBody::class)]
                ),
            ]
        ];
    }
}

interface FirstInterface
{
    /**
     * @RequestMapping("/some/first", method="GET", requestType="application/json", responseType="application/xml")
     */
    public function first(): array;
}

interface SecondInterface
{
    /**
     * @RequestMapping("/some/second", method="GET")
     * @HeaderBag({
     *     @Header("Content-type: application/json")
     * })
     */
    public function second(): string;
}

interface ThirdInterface
{
    /**
     * @RequestMapping("/some/third", method="GET")
     * @ParameterBag({
     *     @Parameter(argumentName="id", alias="thirdId")
     * })
     */
    public function third(int $id): array;
}

interface FourthInterface
{
    /**
     * @RequestMapping("/some/fourth", method="POST")
     * @RequestBody(argumentName="theBody")
     */
    public function fourth(SomeRequestBody $theBody): SomeResponseBody;
}

class SomeRequestBody
{
}

class SomeResponseBody
{
}