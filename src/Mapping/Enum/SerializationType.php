<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Enum;

/**
 * Class SerializationType
 * @method static self JSON()
 * @method static self XML()
 */
final class SerializationType
{
    private const JSON = 'json';
    private const XML = 'xml';
}