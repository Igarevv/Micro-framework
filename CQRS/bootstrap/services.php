<?php

use App\Application\Mappers\UserMapper;
use App\Domain\Book\Repository\BookRepositoryInterface;
use App\Domain\Book\Repository\ImageRepositoryInterface;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Infrastructure\Bus\Command\CommandBus;
use App\Infrastructure\Bus\Command\CommandBusInterface;
use App\Infrastructure\Bus\Query\QueryBus;
use App\Infrastructure\Bus\Query\QueryBusInterface;
use App\Infrastructure\Persistence\Repository\BookRepository;
use App\Infrastructure\Persistence\Repository\CloudinaryRepository;
use App\Infrastructure\Persistence\Repository\UserRepository;
use App\Infrastructure\Persistence\Types\EmailType;
use App\Infrastructure\Persistence\Types\FirstNameType;
use App\Infrastructure\Persistence\Types\HashedPasswordType;
use App\Infrastructure\Persistence\Types\IsbnType;
use App\Infrastructure\Persistence\Types\LastNameType;
use App\Infrastructure\Persistence\Types\YearType;
use App\Infrastructure\Services\EntityManager\Contracts\EntityManagerServiceInterface;
use App\Infrastructure\Services\EntityManager\EntityManagerService;
use App\Providers\ServiceProvider;
use App\Repository\AbstractRepository;
use Cloudinary\Cloudinary;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Igarevv\Micrame\Controller\Controller;
use Igarevv\Micrame\Database\DatabaseConnection;
use Igarevv\Micrame\Events\EventDispatcher;
use Igarevv\Micrame\Http\Kernel;
use Igarevv\Micrame\Http\Middleware\Default\RouteInfoMiddleware;
use Igarevv\Micrame\Http\Middleware\Default\RouteMiddleware;
use Igarevv\Micrame\Http\Middleware\RequestHandler;
use Igarevv\Micrame\Http\Middleware\RequestHandlerInterface;
use Igarevv\Micrame\Http\Request\Request;
use Igarevv\Micrame\Http\Request\RequestInterface;
use Igarevv\Micrame\Router\Router;
use Igarevv\Micrame\Router\RouterInterface;
use Igarevv\Micrame\Session\AuthSession;
use Igarevv\Micrame\Session\Session;
use Igarevv\Micrame\Session\SessionInterface;
use Igarevv\Micrame\View\TwigFactory;
use League\Container\Argument\Literal\StringArgument;
use League\Container\Container;
use League\Container\ReflectionContainer;
use Symfony\Component\Dotenv\Dotenv;

/**
 * Application parameters
 */

$env = new Dotenv();
$env->load(dirname(__DIR__).'/.env');

$request = Request::createFromGlobals();

$basePath = dirname(__DIR__);

$routes = require APP_PATH.'/bootstrap/web.php';

$envStatus = $_ENV['APP_ENV'];

$views = $basePath.'/views';

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

$pathToEntities = APP_PATH.'/app/Clean/Infrastructure/Persistence/Entity';

/**
 * Dependencies bindings
 */

$container = new Container();

$container->add(RequestInterface::class, $request);

$container->add('base-path', new StringArgument($basePath));

$container->delegate(new ReflectionContainer(true));

$container->add('APP_ENV', new StringArgument($envStatus));

$container->add(SessionInterface::class, Session::class);

$container->add(ServiceProvider::class)
  ->addArgument(EventDispatcher::class);

/**
 * For kernel and start app
 */

$container->addShared(EventDispatcher::class);

$container->add(RequestHandlerInterface::class, RequestHandler::class)
  ->addArgument($container);

$container->add(RouterInterface::class, Router::class);

$container->add(RouteInfoMiddleware::class)
  ->addArgument($routes);

$container->add(Kernel::class)
  ->addArguments([
    $container,
    $request,
    RequestHandlerInterface::class,
    EventDispatcher::class,
  ]);

$container->inflector(Controller::class)
  ->invokeMethod('setContainer', [$container]);

$container->add(RouteMiddleware::class)
  ->addArguments([
    $container,
    RouterInterface::class,
  ]);

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


/*
 * Doctrine
 */

$container->addShared(EntityManagerInterface::class,
  function () use ($connectionParams, $pathToEntities) {
      Type::addType('HashedPassword', HashedPasswordType::class);
      Type::addType('FirstName', FirstNameType::class);
      Type::addType('LastName', LastNameType::class);
      Type::addType('Email', EmailType::class);
      Type::addType('Year', YearType::class);
      Type::addType('Isbn', IsbnType::class);

      return new EntityManager(
        conn: DriverManager::getConnection($connectionParams),
        config: ORMSetup::createAttributeMetadataConfiguration(
          paths: [$pathToEntities]
        )
      );
  });


$container->addShared(EntityManagerServiceInterface::class,
  EntityManagerService::class)
  ->addArgument(EntityManagerInterface::class);

/**
 * Custom Repositories
 */

$container->add(UserRepositoryInterface::class,
  UserRepository::class)
  ->addArguments([
    EntityManagerServiceInterface::class,
    UserMapper::class
  ]);

$container->addShared(BookRepositoryInterface::class,
BookRepository::class)
    ->addArgument(EntityManagerServiceInterface::class);

$container->addShared(ImageRepositoryInterface::class, CloudinaryRepository::class)
  ->addArgument($cloudinary);

/**
 * CQRS
 */

$container->addShared(CommandBusInterface::class, CommandBus::class)
  ->addArgument($container);

$container->addShared(QueryBusInterface::class, QueryBus::class)
  ->addArgument($container);

/**
 * For Twig
 */

$container->add('twig-factory', TwigFactory::class)
  ->addArgument($views)
  ->addArgument(SessionInterface::class)
  ->addArgument(AuthSession::class);

$container->addShared('twig', function () use ($container) {
    return $container->get('twig-factory')->initialize();
});

return $container;