<?php

declare(strict_types=1);

namespace HttpClientBinder\Annotation;

interface RegistrarInterface
{
    public function register(): void;
}