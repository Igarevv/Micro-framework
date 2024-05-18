<?php

namespace App\Infrastructure\Bus\Command;

use App\Domain\Based\Bus\Command\CommandHandlerInterface;
use App\Domain\Based\Bus\Command\CommandInterface;
use League\Container\Container;
use RuntimeException;

class CommandBus implements CommandBusInterface
{

    public function __construct(
      private readonly Container $container
    ) {}

    public function dispatch(CommandInterface $command, string $handlerClassName): mixed
    {
        if (! is_subclass_of($handlerClassName, CommandHandlerInterface::class)){
            throw new RuntimeException('Not a handler class provided');
        }

        /** @var CommandHandlerInterface $handler */
        $handler = $this->container->get($handlerClassName);

        return $handler->handle($command);
    }

}