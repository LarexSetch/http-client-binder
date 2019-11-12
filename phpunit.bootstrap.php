<?php

declare(strict_types=1);

use Doctrine\Common\Annotations\AnnotationRegistry;

$loader = include __DIR__ . '/vendor/autoload.php';

AnnotationRegistry::registerLoader(function ($className) use ($loader) {
    $loader->loadClass($className);
});

return $loader;