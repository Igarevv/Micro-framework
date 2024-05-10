<?php

namespace App\Services;

use App\DTO\Author;
use App\DTO\Book;
use App\DTO\BookCollection;
use App\DTO\BookPreviewDto;
use App\DTO\Image;
use App\Exceptions\BookException;
use App\Repository\Interfaces\BookRepositoryInterface;
use Cloudinary\Api\Admin\AdminApi;

class BookService
{

    public function __construct(
      protected BookRepositoryInterface $repository,
      protected ImageService $imageService
    ) {}

    public function save(BookCollection $bookCollection, Image $image): void
    {
        $bookId = $this->repository->save($bookCollection);

        try {
            $isUpload = $this->imageService->uploadImage($image);

            if (! $isUpload){
                $this->repository->deleteBook($bookId);
            }
        } catch (\Exception $e){
            $this->repository->deleteBook($bookId);
            throw $e;
        }
    }

    public function deleteBook(int $id): void
    {
        try {
            $imageId = $this->getImageId($id);

            $this->repository->deleteBook($id);

            $this->imageService->deleteImage($imageId);
        } catch (\Throwable $e){
            throw $e;
        }
    }

    public function getBooksForTable(): array
    {
        $booksFromDb = $this->repository->findAllBooksForTable();

        if (! $booksFromDb){
            throw new BookException('Books not found');
        }

        return $booksFromDb;
    }

    public function getBooksForHomePage(int $page, int $limit): array
    {
        $offset = ($page - 1) * $limit;

        $booksFromDb = $this->repository->getBooksForHomePage($limit, $offset);

        if (! $booksFromDb['data']){
            throw new BookException('Books not found');
        }

        $imageIdsFromDb = [];
        foreach ($booksFromDb['data'] as $item){
            $imageIdsFromDb[] = $item['image_cdn_id'];
        }

        $urls = $this->imageService->getImageUrls($imageIdsFromDb);

        $collection = [];
        foreach ($booksFromDb['data'] as $key => $item){
            $collection[] = new BookPreviewDto(
              title: $item['title'],
              firstName: $item['first_name'],
              lastName: $item['last_name'],
              imageUrl: $urls[$key]['url'],
              bookId: $item['id']
            );
        }
        $pagesCount = ceil($booksFromDb['count'] / $limit);

        return ['collection' => $collection, 'pages' => $pagesCount];
    }

    public function getImageId(mixed $id): string
    {
        $imageId = $this->repository->getBookImageId($id);

        if (! $imageId){
            throw new BookException("Book {$id} not found");
        }

        return $imageId;
    }

    public function bookDto(array $bookData, string $image): Book
    {
        return Book::fromState(
          title: $bookData['title'],
          year: $bookData['year'],
          genre: $bookData['genre'],
          description: $bookData['description'],
          image: $image,
          isbn: $bookData['isbn']
        );
    }

    public function authorDto(array $authorData): Author
    {
        return Author::fromState(
          name: $authorData['first_name'],
          surname: $authorData['last_name']
        );
    }

    public function imageDto(array $imageData): Image
    {
        return $this->imageService->imageDto($imageData);
    }

    public function createFullBookEntity(Book $book, Author $author): BookCollection
    {
        return new BookCollection(
          book: $book,
          author: $author,
        );
    }

    private function getBook(array $books): \Generator
    {
        foreach ($books as $book){
            yield $book;
        }
    }

}