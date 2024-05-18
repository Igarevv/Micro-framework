<?php

namespace App\Infrastructure\Bus\Command;

use App\Domain\Based\Bus\Command\CommandInterface;

interface CommandBusInterface
{
    public function dispatch(CommandInterface $command, string $handlerClassName): mixed;
}