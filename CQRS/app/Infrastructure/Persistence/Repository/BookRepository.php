<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Book\Repository\BookRepositoryInterface;
use App\Domain\Book\ValueObject\Isbn;
use App\Infrastructure\Persistence\Entity\Book;
use App\Infrastructure\Persistence\Entity\BookAuthor;
use App\Infrastructure\Services\EntityManager\Contracts\EntityManagerServiceInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class BookRepository implements BookRepositoryInterface
{

    private EntityRepository $repository;

    public function __construct(
      private readonly EntityManagerServiceInterface $entityManager
    ) {
        $this->repository = $this->entityManager->getRepository(Book::class);
    }

    public function save(BookAuthor $book): void
    {
        $this->entityManager->sync($book);
    }

    public function getById(int $id): ?Book
    {
        return $this->repository->findBy(['id' => $id])[0];
    }

    public function isBookExist(Isbn $isbn): bool
    {
        $book = $this->repository->findOneBy(
          ['isbn' => $isbn->value()]
        );

        if ($book){
            return true;
        }

        return false;
    }

    public function getAll()
    {
        // TODO: Implement getAll() method.
    }

    public function getPaginated(int $limit, int $offset): Paginator
    {
        $dql = "SELECT ba, b, a
            FROM App\Infrastructure\Persistence\Entity\Book b
            JOIN b.bookAuthors ba
            JOIN ba.author a
            ORDER BY ba.id ASC";

        $query = $this->entityManager->createQuery($dql)
          ->setFirstResult($limit)
          ->setMaxResults($offset);

        return new Paginator($query);
    }

    public function delete(int $id): void
    {
        $book = $this->entityManager->getReference(Book::class, $id);

        if ($book){
            $this->entityManager->delete($book, true);
        }
    }

}