<?php

use Igarevv\Micrame\Console\Commands\MigrateCommand;
use Igarevv\Micrame\Console\ConsoleApplication;
use Igarevv\Micrame\Console\ConsoleKernel;
use League\Container\Container;
use Symfony\Component\Dotenv\Dotenv;
use Igarevv\Micrame\Database\DatabaseConnection;
use Doctrine\DBAL\Connection;

/**
 * Console parameters
 */

$env = new Dotenv();
$env->load(APP_PATH . '/.env');

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

$container->add('migrate', MigrateCommand::class)
    ->addArgument(Connection::class);

return $container;