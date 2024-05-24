<?php

namespace App\Application\Mappers;

use App\Domain\Book\Entity\BookEntity;
use App\Domain\Book\ValueObject\Isbn;
use App\Domain\Book\ValueObject\Title;
use App\Domain\Book\ValueObject\Year;
use App\Infrastructure\Persistence\Entity\Book;

class BookMapper
{

    public function toDomain(Book $book): BookEntity
    {
        $bookAuthor = $book->getBookAuthors()->getValues()[0];
        $book = $bookAuthor->getBook();
        $author = $bookAuthor->getAuthor();

        return new BookEntity(
          bookId: $book->getId(),
          title: $book->getTitle()->value(),
          description: $book->getDescription(),
          isbn: $book->getIsbn()->value(),
          year: $book->getYear()->value(),
          authorFirstName: $author->getFirstName()->value(),
          authorLastName: $author->getLastName()->value(),
          genre: implode(', ', $book->getGenre()),
          createdAt: $bookAuthor->getDateTime()->format('Y-m-D H:i'),
          image: $book->getImageId()
        );
    }

    /**
     * @throws \App\Domain\Based\Exception\InvalidFormat
     */
    public function fromDomain(BookEntity $entity): Book
    {
        $genre = explode(', ', $entity->getGenre());

        return Book::create(
          title: Title::fromString($entity->getTitle()),
          year: Year::fromString($entity->getYear()),
          description: $entity->getDescription(),
          genre: $genre,
          isbn: Isbn::fromString($entity->getIsbn())
        );
    }

}