<?php

namespace App\Application\UseCase\Book\SaveCSV;

use App\Domain\Based\Bus\Command\CommandHandlerInterface;
use App\Domain\Based\Bus\Command\CommandInterface;
use App\Domain\Based\Exception\InvalidFormat;
use App\Domain\Book\Repository\BookRepositoryInterface;
use App\Infrastructure\Persistence\Entity\Author;
use App\Infrastructure\Persistence\Entity\Book;
use App\Infrastructure\Persistence\Entity\BookAuthor;
use App\Infrastructure\Services\BookCsvService;

class SaveCsvBookHandler implements CommandHandlerInterface
{

    public function __construct(
      private BookRepositoryInterface $bookRepository,
      private BookCsvService $bookCsvService
    ) {}

    /**
     * @throws \App\Domain\Based\Exception\InvalidFormat
     */
    public function handle(CommandInterface $command): void
    {
        $books = $this->getBooksFromCsv($command);

        $entities = $this->makeEntities($books);

        $this->bookRepository->saveBooksFromCsv($entities);

        // У ТЕБЯ ПОЧЕМУ ТО ПОПАДАЮТ ISBN меньше 13 чисел
        // подсказка, проблема в лидирующих нулях postgresql :)
        // ПРОВЕРИТЬ ВЕСЬ ФУНКЦИОНАЛ
    }

    private function makeEntities(array $books): array
    {
        $entities = [];

        foreach ($books as $book){
            foreach ($book as $bookItem) {
                $author = Author::create(
                  firstName: $bookItem->firstName,
                  lastName: $bookItem->lastName
                );

                $bookE = Book::create(
                  title: $bookItem->title,
                  year: $bookItem->year,
                  description: $bookItem->description,
                  genre: $bookItem->genre,
                  isbn: $bookItem->isbn
                );

                $bookAuthor = new BookAuthor($bookE, $author);

                $bookE->addBookAuthor($bookAuthor);
                $author->addBookAuthor($bookAuthor);

                $entities[] = $bookAuthor;
            }
        }

        return $entities;
    }

    private function getBooksFromCsv(CommandInterface $command): array
    {
        try {
            $books = $this->bookCsvService->getBooksData($command->getFiles());
        } catch (InvalidFormat $e){
            throw $e;
        }

        return $books;
    }
}