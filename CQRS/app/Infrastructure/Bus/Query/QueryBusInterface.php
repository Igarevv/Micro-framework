<?php

namespace App\Infrastructure\Bus\Query;

use App\Domain\Based\Bus\Query\QueryInterface;

interface QueryBusInterface
{
    public function dispatch(QueryInterface $query, string $queryHandler): mixed;
}