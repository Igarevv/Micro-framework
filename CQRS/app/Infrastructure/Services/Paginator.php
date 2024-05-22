<?php

namespace App\Infrastructure\Services;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

class Paginator extends \Doctrine\ORM\Tools\Pagination\Paginator
{
    private Query $query;

    public function __construct(
      Query|QueryBuilder $query,
      bool $fetchJoinCollection = true
    ) {
        $this->query = $query;

        parent::__construct($query, $fetchJoinCollection);
    }

    public function getArrayData(): array
    {
        return $this->query->execute();
    }

}