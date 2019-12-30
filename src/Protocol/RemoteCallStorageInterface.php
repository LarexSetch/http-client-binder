<?php

namespace HttpClientBinder\Protocol;

use HttpClientBinder\Protocol\RemoteCall\RemoteCallInterface;

interface RemoteCallStorageInterface
{
    public function set(string $name, RemoteCallInterface $remoteCall): self;

    public function get(string $name): RemoteCallInterface;
}