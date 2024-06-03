<?php

namespace App\Domain\Book\Repository;

use App\Domain\Book\Service\PaginatorInterface;
use App\Domain\Book\ValueObject\Isbn;
use App\Infrastructure\Persistence\Entity\Book;

interface PublicBookRepositoryInterface
{
    public function getOneForBookPage(string $bookId): Book;

    public function getById(int $id): ?Book;

    public function getPreviewBooks(int $limit, int $offset): PaginatorInterface;

    public function isBookExist(Isbn $isbn): bool;
}