<?php

namespace App\Domain\Based\Bus\Command;

interface CommandHandlerInterface
{
    public function handle(CommandInterface $command);
}