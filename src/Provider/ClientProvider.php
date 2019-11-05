<?php

declare(strict_types=1);

namespace HttpClientBinder\Provider;

use HttpClientBinder\Annotation\Client;
interface ClientProvider
{
    public function provide(): Client;//TODO replace annotation to dto
}