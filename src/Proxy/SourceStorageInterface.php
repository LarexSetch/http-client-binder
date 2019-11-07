<?php

declare(strict_types=1);

namespace HttpClientBinder\Proxy;

interface SourceStorageInterface
{
    public function store(string $className, $source): void;

    public function import(string $className): void;

    public function remove(string $className): void;

    public function exists(string $className): bool;
}