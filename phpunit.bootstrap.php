<?php

declare(strict_types=1);

use Doctrine\Common\Annotations\AnnotationRegistry;

define('TMP_DIR', dirname(__FILE__) . '/var/tmp');

$loader = include __DIR__ . '/vendor/autoload.php';

AnnotationRegistry::registerLoader(function ($className) use ($loader) {
    $loader->loadClass($className);
});

return $loader;