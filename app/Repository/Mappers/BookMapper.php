<?php

namespace App\Repository\Mappers;

use App\DTO\BookPreviewDto;
use App\DTO\TableBookDto;
use App\Entities\Author;
use App\Entities\Book;
use App\Entities\BookCollection;

class BookMapper
{

    public function convertDataToBook(array $data): Book
    {
        return Book::fromState(
          title: $data['title'],
          year: $data['year'],
          genre: $data['genre'],
          description: $data['description'],
          image: $data['image_cdn_id'],
          isbn: $data['isbn'],
          id: $data['id'] ?? null
        );
    }

    public function convertDataToAuthor(array $data): Author
    {
        return Author::fromState(
          name: $data['first_name'],
          surname: $data['last_name'],
          id: $data['id'] ?? null
        );
    }

    public function convertDataToBookCollectionForTable(array $data): array
    {
        $collection = [];
        foreach ($data as $item) {
            $collection[] = new TableBookDto(
              bookId: $item['id'],
              title: $item['title'],
              firstName: $item['first_name'],
              lastName: $item['last_name'],
              isbn: $item['isbn'],
              year: $item['year'],
              time: $item['created_at']
            );
        }

        return $collection;
    }

    public function convertDataToBookCollection(array $data): array
    {
        $collection = [];

        foreach ($data as $item) {
            $collection[] = new BookCollection(
              $this->convertDataToBook($item),
              $this->convertDataToAuthor($item)
            );
        }

        return $collection;
    }

    public function convertDataToBookCollectionPreview(array $data): array
    {
        $collection = [];

        foreach ($data as $item) {
            $collection[] = new BookPreviewDto(
              title: $item['title'],
              firstName: $item['first_name'],
              lastName: $item['last_name'],
              imageId: $item['image_cdn_id'],
              bookId: $item['id']
            );
        }

        return $collection;
    }

}