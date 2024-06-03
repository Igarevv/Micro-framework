<?php

namespace App\Domain\Book\Repository;

use App\Domain\Book\Service\PaginatorInterface;
use App\Domain\Book\ValueObject\Isbn;
use App\Infrastructure\Persistence\Entity\Book;
use App\Infrastructure\Persistence\Entity\BookAuthor;
use App\Infrastructure\Services\Paginator;
use Doctrine\ORM\Mapping\Entity;

interface BookManagementRepositoryInterface
{
    public function save(BookAuthor $book): void;

    public function saveBooksFromCsv(array $entities): void;

    public function getPublishedBooksPaginated(int $limit, int $offset): PaginatorInterface;

    public function getStagedBooksPaginated(int $limit, int $offset): PaginatorInterface;

    public function delete(int $id): void;

    public function updateBookImageData(int $bookId, ?string $imageId): void;

}