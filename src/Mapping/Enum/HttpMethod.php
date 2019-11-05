<?php

declare(strict_types=1);

namespace HttpClientBinder\Mapping\Enum;

use HttpClientBinder\Base\Enum;

final class HttpMethod extends Enum
{
    private const GET = 'GET';
    private const POST = 'POST';
    private const PUT = 'PUT';
    private const DELETE = 'DELETE';
}