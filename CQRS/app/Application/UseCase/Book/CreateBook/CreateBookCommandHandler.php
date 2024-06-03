<?php

namespace App\Application\UseCase\Book\CreateBook;

use App\Domain\Based\Bus\Command\CommandHandlerInterface;
use App\Domain\Based\Bus\Command\CommandInterface;
use App\Domain\Based\ValueObject\FirstName;
use App\Domain\Based\ValueObject\LastName;
use App\Domain\Book\Exception\BookException;
use App\Domain\Book\Repository\BookManagementRepositoryInterface;
use App\Domain\Book\ValueObject\Isbn;
use App\Domain\Book\ValueObject\Title;
use App\Domain\Book\ValueObject\Year;
use App\Infrastructure\Persistence\Entity\Author;
use App\Infrastructure\Persistence\Entity\Book;
use App\Infrastructure\Persistence\Entity\BookAuthor;

class CreateBookCommandHandler implements CommandHandlerInterface
{

    public function __construct(
      private readonly BookManagementRepositoryInterface $repository
    ) {}

    /**
     * @param  CreateBookCommand  $command
     *
     * @throws \App\Domain\Book\Exception\BookException
     * @throws \App\Domain\Based\Exception\InvalidFormat
     */
    public function handle(CommandInterface $command): ?int
    {
        $isbn = Isbn::fromString($command->getIsbn());

        $this->ensureBookExists($isbn);

        $author = Author::create(
          FirstName::fromString($command->getAuthorFirstName()),
          LastName::fromString($command->getAuthorLastName())
        );

        $book = Book::create(
          title: Title::fromString($command->getTitle()),
          year: Year::fromString($command->getYear()),
          description: $command->getDescription(),
          genre: $command->getGenres(),
          isbn: $isbn,
          imageId: $command->getImageId()
        );

        $bookAuthor = BookAuthor::create($book, $author);

        $book->addBookAuthor($bookAuthor);
        $author->addBookAuthor($bookAuthor);

        $this->repository->save($bookAuthor);

        return $book->getId();
    }

    private function ensureBookExists(Isbn $isbn): void
    {
        if ($this->repository->isBookExist($isbn)) {
            throw new BookException('Book with this ISBN is already exists');
        }
    }

}