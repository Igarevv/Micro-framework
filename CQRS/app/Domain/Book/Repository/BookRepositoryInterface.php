<?php

namespace App\Domain\Book\Repository;

use App\Domain\Book\ValueObject\Isbn;
use App\Infrastructure\Persistence\Entity\Book;
use App\Infrastructure\Persistence\Entity\BookAuthor;
use App\Infrastructure\Services\Paginator;
use Doctrine\ORM\Mapping\Entity;

interface BookRepositoryInterface
{
    public function save(BookAuthor $book): void;

    public function getById(int $id): ?Book;

    public function isBookExist(Isbn $isbn): bool;

    public function getOneForBookPage(string $bookId): Book;

    public function getPublishedBooksPaginated(int $limit, int $offset): Paginator;

    public function getStagedBooksPaginated(int $limit, int $offset): Paginator;

    public function delete(int $id): void;

    public function saveBooksFromCsv(array $entities): void;

    public function updateImageData(int $bookId, ?string $imageId): void;

}