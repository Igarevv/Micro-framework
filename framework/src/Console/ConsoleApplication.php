<?php

namespace Igarevv\Micrame\Console;

use Igarevv\Micrame\Console\Commands\CommandInterface;
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

        $command = $argv[1] ?? null;

        if ( ! $command){
            throw new NullCommandException('Command name is required');
        }

        $params = array_slice($argv, 2);
        $options = $this->parseParamsFromConsole($params);

        /** @var CommandInterface $commandExecutor*/

        $commandExecutor = $this->container->get($command);

        return $commandExecutor->execute($options);
    }

    private function parseParamsFromConsole(array $parameters): array
    {
        $options = [];

        foreach ($parameters as $param){
            if (str_starts_with($param, '--')){
                $option = explode('=', substr($param, 2));
                $options[$option[0]] = $option[1] ?? true;
            }
        }

        return $options;
    }
}