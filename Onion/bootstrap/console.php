<?php

use Igarevv\Micrame\Console\Commands\MigrateCommand;
use Igarevv\Micrame\Console\ConsoleApplication;
use Igarevv\Micrame\Console\ConsoleKernel;
use League\Container\Argument\Literal\StringArgument;
use League\Container\Container;
use Igarevv\Micrame\Database\DatabaseConnection;
use Doctrine\DBAL\Connection;
use Symfony\Component\Dotenv\Dotenv;

/**
 * Console parameters
 */

$basePath = dirname(__DIR__);

$env = new Dotenv();
$env->load($basePath. '/.env');

$migrationPath = $basePath.'/database/migrations';
$container = new Container();

$connectionParams = [
  'dbname' => $_ENV['DB_NAME'],
  'user' => $_ENV['DB_USER'],
  'password' => $_ENV['DB_PASS'],
  'host' => $_ENV['DB_HOST'],
  'driver' => $_ENV['DB_DRIVER'],
];

/**
 * Console dependencies
 */

$container->add('base-path', new StringArgument($basePath));

$container->add(ConsoleApplication::class)
  ->addArgument($container);

$container->add(ConsoleKernel::class)
  ->addArgument($container)
  ->addArgument(ConsoleApplication::class);

$container->add(DatabaseConnection::class)
    ->addArgument($connectionParams);

$container->addShared(Connection::class, function () use ($container): Connection{
    return $container->get(DatabaseConnection::class)->connect();
});

$container->add('migration:migrate', MigrateCommand::class)
    ->addArgument(Connection::class)
    ->addArgument(new StringArgument($migrationPath));

return $container;