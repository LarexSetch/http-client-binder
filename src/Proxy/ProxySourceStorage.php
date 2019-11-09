<?php

declare(strict_types=1);

namespace HttpClientBinder\Proxy;

final class ProxySourceStorage implements SourceStorageInterface
{
    /**
     * @var string
     */
    private $directory;

    public function __construct(string $directory)
    {
        $this->directory = $directory;
        $this->checkDirectory();
    }

    public function store(string $className, string $source): void
    {
        file_put_contents($this->getFileRealpath($className), $source);
    }

    public function import(string $className): void
    {
        require_once $this->getFileRealpath($className);
    }

    public function remove(string $className): void
    {
        unlink($this->getFileRealpath($className));
    }

    public function exists(string $className): bool
    {
        return is_file($this->getFileRealpath($className));
    }

    private function checkDirectory(): void
    {
        if (!is_dir($this->directory)) {
            mkdir($this->directory, 0777, true);
        }
    }

    private function getFileRealpath($className): string
    {
        return
            sprintf(
                "%s/%s.php",
                $this->directory,
                str_replace('\\', '_', $className)
            );
    }
}