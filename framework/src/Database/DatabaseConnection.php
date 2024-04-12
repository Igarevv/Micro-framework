<?php

namespace Igarevv\Micrame\Database;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class DatabaseConnection
{
    public function __construct(
      private array $params
    ) {}

    public function connect(): Connection
    {
        $connection = DriverManager::getConnection($this->params);

        return $connection;
    }
}