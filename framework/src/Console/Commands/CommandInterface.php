<?php

namespace Igarevv\Micrame\Console\Commands;

interface CommandInterface
{
    public function execute(array $params = []): int;
}