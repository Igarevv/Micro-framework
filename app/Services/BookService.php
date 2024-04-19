<?php

namespace App\Services;

use App\DTO\Image;
use App\DTO\TableBookData;
use App\Entities\Author;
use App\Entities\Book;
use App\Entities\BookCollection;
use App\Exceptions\BookException;
use App\Repository\Interfaces\BookRepositoryInterface;

class BookService
{

    public function __construct(
      protected BookRepositoryInterface $repository
    ) {}

    public function save(BookCollection $bookCollection, ImageService $imageService): void
    {
        try {
            $this->repository->save($bookCollection);

            $imageService->uploadImage($bookCollection->image);
        } catch (\Throwable $e){
            throw $e;
        }
    }

    public function getBooksForTable(): array
    {
        $booksFromDb = $this->repository->findAllBookForTable();

        if (! $booksFromDb){
            throw new BookException('Books not found');
        }

        $booksInstance = [];
        foreach ($this->getBook($booksFromDb) as $book){
            $booksInstance[] = new TableBookData(
              bookId: $book['id'],
              title: $book['title'],
              firstName: $book['first_name'],
              lastName: $book['last_name'],
              isbn: $book['isbn'],
              year: $book['year'],
              time: $book['created_at']
            );
        }

        return $booksInstance;
    }

    public function deleteBook(int $id, ImageService $imageService): void
    {
        try {
            $imageId = $this->getImageId($id);

            $this->repository->deleteBook($id);

            $result = $imageService->deleteImage($imageId);
        } catch (\Throwable $e){
            throw $e;
        }
    }

    public function getImageId(mixed $id): string
    {
        $imageId = $this->repository->getBookImageId($id);

        if (! $imageId){
            throw new BookException("Book {$id} not found");
        }

        return $imageId;
    }

    public function bookEntity(array $bookData, string $image): Book
    {
        return Book::fromCreate(
          title: $bookData['title'],
          year: $bookData['year'],
          genre: $bookData['genre'],
          description: $bookData['description'],
          image: $image,
          isbn: $bookData['isbn']
        );
    }


    public function authorEntity(array $authorData): Author
    {
        return Author::fromCreate(
          name: $authorData['first_name'],
          surname: $authorData['last_name']
        );
    }

    public function createFullBookEntity(array $bookData, array $authorData, Image $image): BookCollection
    {
        $author = $this->authorEntity($authorData);
        $book = $this->bookEntity($bookData, $image->getFileName());

        return new BookCollection(
          book: $book,
          author: $author,
          image: $image
        );
    }

    private function getBook(array $books): \Generator
    {
        foreach ($books as $book){
            yield $book;
        }
    }

}