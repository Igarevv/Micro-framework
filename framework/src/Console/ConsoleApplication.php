<?php

namespace Igarevv\Micrame\Console;

use Igarevv\Micrame\Console\Commands\CommandInterface;
use Igarevv\Micrame\Console\Exceptions\ConsoleException;
use Igarevv\Micrame\Console\Exceptions\NullCommandException;
use Psr\Container\ContainerInterface;

class ConsoleApplication
{

    public function __construct(
      private ContainerInterface $container
    ) {}

    public function run(): int
    {
        $argv = $_SERVER['argv'];

        $commandType = $argv[1] ?? null;
        $action = $argv[2] ?? null;

        if ( ! $commandType && ! $action) {
            throw new NullCommandException('Command line required type and action');
        }

        $params = array_slice($argv, 3);
        $options = $this->parseParamsFromConsole($params);

        /** @var CommandInterface $commandExecutor */

        try {
            $commandExecutor = $this->container->get("{$commandType}:{$action}");
        } catch (\Throwable $e) {
            throw new ConsoleException('Command not found');
        }

        return $commandExecutor->execute($options);
    }

    private function parseParamsFromConsole(array $parameters): array
    {
        $options = [];

        foreach ($parameters as $param) {
            if (str_starts_with($param, '--')) {
                $option = explode('=', substr($param, 2));
                $options[$option[0]] = $option[1] ?? true;
            }
        }

        return $options;
    }

}