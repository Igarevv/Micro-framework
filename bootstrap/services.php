<?php

use App\Repository\AbstractRepository;
use App\Repository\BookRepository;
use App\Repository\ImageCloudinaryRepository;
use App\Repository\Interfaces\BookRepositoryInterface;
use App\Repository\Interfaces\ImageRepositoryInterface;
use App\Repository\Mappers\BookMapper;
use Cloudinary\Cloudinary;
use Doctrine\DBAL\Connection;
use Igarevv\Micrame\Controller\Controller;
use Igarevv\Micrame\Database\DatabaseConnection;
use Igarevv\Micrame\Http\Kernel;
use Igarevv\Micrame\Http\Request;
use Igarevv\Micrame\Router\Router;
use Igarevv\Micrame\Router\RouterInterface;
use Igarevv\Micrame\Session\Session;
use Igarevv\Micrame\Session\SessionInterface;
use Igarevv\Micrame\View\TwigFactory;
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
$env->load(APP_PATH.'/.env');

$request = Request::createFromGlobals();
$routes = require APP_PATH.'/bootstrap/web.php';
$envStatus = $_ENV['APP_ENV'];
$views = APP_PATH.'/views';

$connectionParams = [
  'dbname' => $_ENV['DB_NAME'],
  'user' => $_ENV['DB_USER'],
  'password' => $_ENV['DB_PASS'],
  'host' => $_ENV['DB_HOST'],
  'driver' => $_ENV['DB_DRIVER'],
];

$cloudinary = new Cloudinary([
  'cloud' => [
    'cloud_name' => $_ENV['CLOUD_NAME'],
    'api_key' => $_ENV['CLOUD_API'],
    'api_secret' => $_ENV['API_SECRET'],
  ],
]);

/**
 * Dependencies bindings
 */

$container = new Container();

$container->delegate(new ReflectionContainer(true));

$container->add('APP_ENV', new StringArgument($envStatus));

$container->add(SessionInterface::class, Session::class);

/**
 * For kernel and start app
 */

$container->add(RouterInterface::class, Router::class);

$container->add(Kernel::class)
  ->addArgument(RouterInterface::class)
  ->addArgument($container)
  ->addArgument($request);
$container->extend(RouterInterface::class)
  ->addMethodCall('setRoutes', [new ArrayArgument($routes)]);

$container->inflector(Controller::class)
  ->invokeMethod('setContainer', [$container])
  ->invokeMethod('setSession', [$container->get(SessionInterface::class)]);

/**
 * For database layer
 */

$container->add(DatabaseConnection::class)
  ->addArgument($connectionParams);

$container->addShared(Connection::class, function () use ($container) {
    return $container->get(DatabaseConnection::class)->connect();
});

$container->inflector(AbstractRepository::class)
  ->invokeMethod('setConnection', [$container->get(Connection::class)]);

/**
 * For Twig
 */

$container->add('twig-factory', TwigFactory::class)
    ->addArgument($views)
    ->addArgument(SessionInterface::class);

$container->addShared('twig', function () use ($container) {
    return $container->get('twig-factory')->initialize();
});

/**
 * Custom database dependencies
 */

$container->add(BookRepositoryInterface::class, BookRepository::class)
  ->addArgument(BookMapper::class);

$container->add(ImageRepositoryInterface::class,
  ImageCloudinaryRepository::class)
  ->addArgument($cloudinary);

return $container;