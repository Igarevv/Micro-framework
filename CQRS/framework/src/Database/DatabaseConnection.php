<?php

namespace Igarevv\Micrame\Database;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class DatabaseConnection
{
    public function __construct(
      private readonly array $params
    ) {}

    public function connect(): Connection
    {
        return DriverManager::getConnection($this->params);
    }
}