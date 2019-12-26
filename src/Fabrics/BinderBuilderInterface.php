<?php

declare(strict_types=1);

namespace HttpClientBinder\Fabrics;

use HttpClientBinder\Codec\DecoderInterface;
use HttpClientBinder\Codec\EncoderInterface;

interface BinderBuilderInterface
{
    public static function builder(): self;

    public function encoder(EncoderInterface $encoder): self;

    public function decoder(DecoderInterface $decoder): self;

    public function temporaryDirectory(string $temporaryDirectory): self;

    public function target(string $className, string $url = null): self;

    /**
     * Returns instance of $className in method target
     * @return mixed
     */
    public function getClient();
}