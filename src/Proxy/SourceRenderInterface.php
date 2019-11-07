<?php

declare(strict_types=1);

namespace HttpClientBinder\Proxy;

use HttpClientBinder\Proxy\Dto\RenderData;

interface SourceRenderInterface
{
    public function render(RenderData $renderData): string;
}