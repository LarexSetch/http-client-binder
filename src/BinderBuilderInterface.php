<?php

declare(strict_types=1);

namespace HttpClientBinder;

interface BinderBuilderInterface
{
    public static function builder(): self;

    public function target(string $className, string $url): self;

    /**
     * Returns instance of $className in method target
     * @return mixed
     */
    public function getClient();
}