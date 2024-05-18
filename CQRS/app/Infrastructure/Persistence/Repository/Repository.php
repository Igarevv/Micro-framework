<?php

namespace App\Infrastructure\Persistence\Repository;

use Doctrine\DBAL\Connection;

class Repository
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