<?php

declare(strict_types=1);

namespace HttpClientBinder\Annotation;

enum ParameterType: string
{
    case QUERY = 'query';
    case PATH = 'path';
    case BODY = 'body';
    case HEADER = 'header';
}
