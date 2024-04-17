<?php

namespace App\Services;

use App\DTO\Image;
use App\Entities\Author;
use App\Entities\Book;
use App\Entities\BookCollection;
use App\Repository\BookRepository;

class BookService
{

    public function __construct(
      protected BookRepository $repository
    ) {}

    public function save(BookCollection $bookCollection): void
    {
        $this->repository->save($bookCollection);
    }

    public function bookEntity(array $bookData, string $image): Book
    {
        return new Book(
          title: $bookData['title'],
          year: $bookData['year'],
          genre: $bookData['genre'],
          description: $bookData['description'],
          image: $image
        );
    }

    public function authorEntity(array $authorData): Author
    {
        return new Author(
          name: $authorData['first_name'],
          surname: $authorData['last_name']
        );
    }

    public function createBook(array $bookData, array $authorData, Image $image): BookCollection
    {
        $author = $this->authorEntity($authorData);
        $book = $this->bookEntity($bookData, $image->getFullName());

        return new BookCollection(
          book: $book,
          author: $author,
          image: $image
        );
    }
}