<?php

use Igarevv\Micrame\Controller\Controller;
use Igarevv\Micrame\Http\Kernel;
use Igarevv\Micrame\Http\Request;
use Igarevv\Micrame\Router\Router;
use Igarevv\Micrame\Router\RouterInterface;
use League\Container\Argument\Literal\ArrayArgument;
use League\Container\Argument\Literal\StringArgument;
use League\Container\Container;
use League\Container\ReflectionContainer;
use Symfony\Component\Dotenv\Dotenv;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Application parameters
 */

$env = new Dotenv();
$env->load(APP_PATH . '/.env');

$request = Request::createFromGlobals();
$routes = require APP_PATH . '/bootstrap/web.php';
$envStatus = $_ENV['APP_ENV'];
$views = APP_PATH . '/views';


/**
 * Dependencies bindings
 */

$container = new Container();

$container->delegate(new ReflectionContainer(true));

$container->add(RouterInterface::class, Router::class);

$container->add(Kernel::class)
    ->addArgument(RouterInterface::class)
    ->addArgument($container)
    ->addArgument($request);

$container->add('APP_ENV', new StringArgument($envStatus));

$container->extend(RouterInterface::class)
    ->addMethodCall('setRoutes', [new ArrayArgument($routes)]);

$container->inflector(Controller::class)
    ->invokeMethod('setContainer', [$container]);

$container->addShared('twig-file-loader', FilesystemLoader::class)
    ->addArgument($views);

$container->addShared('twig', Environment::class)
    ->addArgument('twig-file-loader');

return $container;