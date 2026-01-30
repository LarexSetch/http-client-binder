<?php

declare(strict_types=1);

define('TMP_DIR', dirname(__FILE__) . '/var/tmp');
const WIREMOCK_HOST = 'http://localhost:8080';

$loader = include __DIR__ . '/vendor/autoload.php';

return $loader;