# Http client binder
Request

Enter container with bash

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
