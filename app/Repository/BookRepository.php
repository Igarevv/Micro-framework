<?php

namespace App\Repository;

use App\Entities\BookCollection;
use App\Repository\Interfaces\BookInterface;
use Doctrine\DBAL\Query\QueryBuilder;

class BookRepository extends AbstractRepository implements BookInterface
{

    public function save(BookCollection $bookCollection): void
    {
        $builder = $this->db()->createQueryBuilder();
        $this->db()->transactional(function () use ($builder, $bookCollection) {
            $bookId = $this->saveBook($builder, $bookCollection);

            $authorId = $this->saveAuthor($builder, $bookCollection);

            $builder->insert('book_genre_author')
              ->values(['book_id' => '?', 'author_id' => '?', 'genre' => '?'])
              ->setParameter(0, $bookId)
              ->setParameter(1, $authorId)
              ->setParameter(2, $bookCollection->book->getGenre())
              ->executeQuery();
        });
    }

    public function findAll()
    {
        // TODO: Implement findAll() method.
    }

    private function saveBook(
      QueryBuilder $builder,
      BookCollection $bookCollection
    ): string|int {
        $builder->insert('book')
          ->values([
            'title'        => ':title',
            'year'         => ':year',
            'description'  => ':description',
            'image_cdn_id' => ':image_cdn_id',
            'isbn'         => ':isbn'
          ])
          ->setParameters([
            'title'        => $bookCollection->book->getTitle(),
            'year'         => $bookCollection->book->getYear(),
            'description'  => $bookCollection->book->getDescription(),
            'image_cdn_id' => $bookCollection->book->getImage(),
            'isbn'         => $bookCollection->book->getIsbn()
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