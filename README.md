# Http client binder

[![GitHub Actions status | larexsetch/http-client-binder](https://github.com/larexsetch/http-client-binder/workflows/Check%20all%20tests/badge.svg)](https://github.com/LarexSetch/http-client-binder/actions?query=workflow%3A%22Check+all+tests%22)

This library creates simple way bind the interface and http call to foreign endpoint

## Installation

```bash
composer require larexsetch/http-client-binder
```

## Create client interface

You can see example here: [HttpClientBinder\Tests\Base\Client\ClientInterface](https://github.com/LarexSetch/http-client-binder/blob/master/tests/Base/Client/ClientInterface.php)

## Build the client

```php
<?php

/** @var YourClientInterface $client */
$client = 
    \HttpClientBinder\Fabrics\BinderBuilder::builder(YourClientInterface::class, $baseUrl) // $baseUrl may be declare by annotation @Client(baseUrl="http://example.com")
        ->temporaryDirectory("/path/to/temporary/directory") // Default /tmp
        ->encoder($encoder) // Custom encoder 
        ->decoder($decoder) // Custom decoder
        ->getClient();
```
