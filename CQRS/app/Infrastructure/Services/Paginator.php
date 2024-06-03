<?php

namespace App\Infrastructure\Services;

use App\Domain\Book\Service\PaginatorInterface;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;

class Paginator extends DoctrinePaginator implements PaginatorInterface
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

    public function countPartialSelection(): int
    {
        $dql = "SELECT COUNT(b.id) FROM App\Infrastructure\Persistence\Entity\Book b";

        $query = $this->query->getEntityManager()->createQuery($dql);

        return $query->getResult(AbstractQuery::HYDRATE_SINGLE_SCALAR);
    }

}