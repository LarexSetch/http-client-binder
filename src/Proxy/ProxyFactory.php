<?php

declare(strict_types=1);

namespace HttpClientBinder\Proxy;

use HttpClientBinder\Codec\DecoderInterface;
use HttpClientBinder\Codec\EncoderInterface;
use HttpClientBinder\Metadata\Dto\ClientMetadata;
use HttpClientBinder\Protocol\RemoteCall\RemoteCall;
use HttpClientBinder\Proxy\RequestFactory\GuzzleRequestFactory;
use HttpClientBinder\Proxy\ResultFactory\BaseResultFactory;
use HttpClientBinder\Utils\ClassNameResolver;

final readonly class ProxyFactory
{
    public function __construct(
        private ProxySourceRender $render,
        private EncoderInterface $encoder,
        private DecoderInterface $decoder,
        private string $storageDirectory
    ) {
    }

    public function create(ClientMetadata $metadata, RemoteCall $remoteCall): mixed
    {
        $this->import($metadata);
        $className = ClassNameResolver::resolve($metadata);

        return new $className(
            $remoteCall,
            new GuzzleRequestFactory($this->encoder),
            new BaseResultFactory($this->decoder)
        );
    }

    private function import(ClientMetadata $metadata): void
    {
        if (!is_file($this->getFileRealpath($metadata))) {
            is_dir($this->storageDirectory) or mkdir($this->storageDirectory, 0777, true);
            $source = $this->render->render($metadata);
            file_put_contents($this->getFileRealpath($metadata), $source);
        }

        require_once $this->getFileRealpath($metadata);
    }

    private function getFileRealpath(ClientMetadata $metadata): string
    {
        return
            sprintf(
                "%s/%s.php",
                $this->storageDirectory,
                str_replace('\\', '_', ClassNameResolver::resolve($metadata))
            );
    }
}