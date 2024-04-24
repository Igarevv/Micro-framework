<?php

namespace App\Repository;

use App\DTO\BookCollection;
use App\Repository\Interfaces\BookRepositoryInterface;
use App\Repository\Mappers\BookMapper;
use Doctrine\DBAL\Query\QueryBuilder;

class BookRepository extends AbstractRepository implements
  BookRepositoryInterface
{

    public function __construct(
      private BookMapper $mapper
    ) {}

    public function save(BookCollection $bookCollection): void
    {
        $builder = $this->db()->createQueryBuilder();
        $this->db()->transactional(function () use ($builder, $bookCollection) {
            $bookId = $this->saveBook($builder, $bookCollection);

            $authorId = $this->saveAuthor($builder, $bookCollection);

            $builder->insert('book_author')
              ->values(['book_id' => '?', 'author_id' => '?'])
              ->setParameter(0, $bookId)
              ->setParameter(1, $authorId)
              ->executeQuery();
        });
    }

    public function findAllBooksForTable(): array
    {
        $builder = $this->db()->createQueryBuilder();

        $builder->select(
          'b.id',
          'b.title',
          'b.year',
          'b.isbn',
          'a.last_name',
          'a.first_name',
          'ba.created_at',
        )->from('book', 'b')
          ->join('b', 'book_author', 'ba', 'b.id = ba.book_id')
          ->join('ba', 'author', 'a', 'a.id = ba.author_id');
        $books = $builder->fetchAllAssociative();

        return $this->mapper->convertDataToBookCollectionForTable($books);
    }

    public function findAll() {}

    public function getAllBooksForHomePage(): array
    {
        $builder = $this->db()->createQueryBuilder();

        $builder->select(
          'b.id',
          'b.title',
          'b.image_cdn_id',
          'a.first_name',
          'a.last_name'
        )
          ->from('book', 'b')
          ->join('b', 'book_author', 'ba', 'b.id = ba.book_id')
          ->join('ba', 'author', 'a', 'a.id = ba.author_id');

        $books = $builder->fetchAllAssociative();

        return $books ?? [];
    }

    public function deleteBook(mixed $id): void
    {
        $this->db()->transactional(function () use ($id) {
            $builder = $this->db()->createQueryBuilder();

            $builder->delete('book_author')
              ->where('book_id = ?')
              ->setParameter(0, $id)
              ->executeQuery();
        });
    }

    public function getBookImageId(mixed $id): string|false
    {
        $builder = $this->db()->createQueryBuilder();

        $builder->select('image_cdn_id')
          ->from('book', 'b')
          ->where('b.id = ?')
          ->setParameter(0, $id);

        return $builder->fetchOne();
    }

    private function saveBook(
      QueryBuilder $builder,
      BookCollection $bookCollection
    ): string|int {
        $builder->insert('book')
          ->values([
            'title' => ':title',
            'year' => ':year',
            'description' => ':description',
            'image_cdn_id' => ':image_cdn_id',
            'isbn' => ':isbn',
            'genre' => ':genre',
          ])
          ->setParameters([
            'title' => $bookCollection->book->getTitle(),
            'year' => $bookCollection->book->getYear(),
            'description' => $bookCollection->book->getDescription(),
            'image_cdn_id' => $bookCollection->book->getImage(),
            'isbn' => $bookCollection->book->getIsbn(),
            'genre' => $bookCollection->book->getGenre(),
          ])
          ->executeQuery();

        return $this->db()->lastInsertId();
    }

    private function saveAuthor(
      QueryBuilder $builder,
      BookCollection $bookCollection
    ): string|int {
        $builder->insert('author')
          ->values(['first_name' => '?', 'last_name' => '?'])
          ->setParameter(0, $bookCollection->author->firstName())
          ->setParameter(1, $bookCollection->author->lastName())
          ->executeQuery();

        return $this->db()->lastInsertId();
    }

}