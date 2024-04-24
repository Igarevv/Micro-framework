<?php

use Igarevv\Micrame\Http\Kernel;

define('APP_PATH', dirname(__DIR__));

require APP_PATH . '/vendor/autoload.php';

$container = require APP_PATH . '/bootstrap/services.php';

/** @var Kernel $kernel */
$kernel = $container->get(Kernel::class);

$response = $kernel->handle();

$response->send();

$kernel->cleanUp();