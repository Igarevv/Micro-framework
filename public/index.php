<?php

use Igarevv\Micrame\Http\Kernel;
use Symfony\Component\Dotenv\Dotenv;

define('APP_PATH', dirname(__DIR__));

require APP_PATH . '/vendor/autoload.php';

$env = new Dotenv();
$env->load(APP_PATH . '/.env');

$container = require APP_PATH . '/config/services.php';

$kernel = $container->get(Kernel::class);

$response = $kernel->handle();

$response->send();