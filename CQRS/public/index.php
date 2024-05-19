<?php

use Igarevv\Micrame\Http\Kernel;
use Igarevv\Micrame\Http\Request\RequestInterface;
use League\Container\Container;

define('APP_PATH', dirname(__DIR__));

require APP_PATH . '/vendor/autoload.php';

/** @var Container $container */
$container = require APP_PATH . '/bootstrap/services.php';

/** @var Kernel $kernel */
$kernel = $container->get(Kernel::class);

$response = $kernel->handle();

$response->send();

$kernel->cleanUp($container->get(RequestInterface::class));