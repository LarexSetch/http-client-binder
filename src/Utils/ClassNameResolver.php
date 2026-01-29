<?php

declare(strict_types=1);

namespace HttpClientBinder\Utils;

use HttpClientBinder\Metadata\Dto\ClientMetadata;

final class ClassNameResolver
{
    public static function resolve(ClientMetadata $clientMetadata): string
    {
        return sprintf(
            "%sProxy",
            strtr(
                $clientMetadata->name,
                [
                    "Interface" => "",
                    "\\" => "_",
                ]
            )
        );
    }

}