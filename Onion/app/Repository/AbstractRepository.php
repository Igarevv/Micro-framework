<?php

namespace App\Repository;

use Doctrine\DBAL\Connection;

class AbstractRepository
{

    private ?Connection $connection;
    public function setConnection(Connection $connection): void
    {
        $this->connection = $connection;
    }

    protected function db(): Connection
    {
        return $this->connection;
    }
}