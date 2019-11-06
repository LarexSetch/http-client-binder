<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Enum;

use HttpClientBinder\Base\Enum;

/**
 * @method static self PATH()
 * @method static self QUERY()
 */
final class UrlParameterType extends Enum
{
    private const PATH = 'path';
    private const QUERY = 'query';
    private const HEADER = 'header';
}