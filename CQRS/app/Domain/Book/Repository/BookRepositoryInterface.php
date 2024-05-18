<?php

namespace App\Domain\Book\Repository;

use App\Domain\Book\ValueObject\Isbn;
use App\Infrastructure\Persistence\Entity\Book;
use App\Infrastructure\Persistence\Entity\BookAuthor;
use Doctrine\ORM\Tools\Pagination\Paginator;

interface BookRepositoryInterface
{
    public function save(BookAuthor $book);

    public function getById(int $id): ?Book;

    public function isBookExist(Isbn $isbn): bool;

    public function getAll();

    public function getPaginated(int $limit, int $offset): Paginator;

    public function delete(int $id): void;

}