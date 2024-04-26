<?php

namespace App\Repository\Interfaces;

use App\DTO\BookCollection;

interface BookRepositoryInterface
{
    public function save(BookCollection $bookCollection): int;

    public function findAll();

    public function findAllBooksForTable(): array;

    public function deleteBook(mixed $id);

    public function getBookImageId(mixed $id): string|false;

    public function getAllBooksForHomePage(): array;
}