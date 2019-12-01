# Http client binder

This library creates simple way bind the interface and http call to foreign endpoint

## Example

```php
<?php

use HttpClientBinder\Annotation\Client;
use HttpClientBinder\Annotation\RequestMapping;

/**
 * @Client(baseUrl="http://test.com")
 */
interface SomeRemoteApiInterface {
    /**
     * @RequestMapping("/", method="GET", responseType="application/json")
     */
    public function getItem(int $id): SpecificData;
}

/**
 * Object to deserialize
 */
class SpecificData {}
```

You must build the client

```php

```

```
docker build -f etc/docker/php/Dockerfile localphp
docker run -it --rm --name symfonyclientbinder-php -v .:/usr/src/myapp -w /usr/src/myapp localphp bash
```

Serializer - can connect any one
Deserializer - can connect any one
Get parameters builder - can connect any one
Headers builder - can connect any one
Interceptor - can add any one
Implementation builder

@HeaderLine
@Header("Key", value="valu")
