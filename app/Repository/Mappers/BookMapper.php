<?php

namespace App\Repository\Mappers;

use App\DTO\Author;
use App\DTO\Book;
use App\DTO\BookCollection;
use App\DTO\TableBookDto;

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
              time: $item['created_at'],
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

}