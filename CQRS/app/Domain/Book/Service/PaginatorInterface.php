<?php

namespace App\Domain\Book\Service;

/**
 * @mixin \Doctrine\ORM\Tools\Pagination\Paginator
 */
interface PaginatorInterface
{

    public function countPartialSelection(): int;
    public function getArrayData(): array;
}