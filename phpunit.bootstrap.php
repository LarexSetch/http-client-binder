<?php

declare(strict_types=1);

define('TMP_DIR', dirname(__FILE__) . '/var/tmp');
define('WIREMOCK_HOST', 'http://wiremock:8080');

$loader = include __DIR__ . '/vendor/autoload.php';

return $loader;