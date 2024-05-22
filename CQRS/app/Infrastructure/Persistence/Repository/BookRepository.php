<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Book\Exception\BookException;
use App\Domain\Book\Repository\BookRepositoryInterface;
use App\Domain\Book\ValueObject\Isbn;
use App\Infrastructure\Persistence\Entity\Book;
use App\Infrastructure\Persistence\Entity\BookAuthor;
use App\Infrastructure\Services\EntityManager\Contracts\EntityManagerServiceInterface;
use App\Infrastructure\Services\Paginator;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityRepository;

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

        if ($book) {
            return true;
        }

        return false;
    }

    public function getAll()
    {
        // TODO: Implement getAll() method.
    }

    public function getPublishedBooksPaginated(int $limit, int $offset): Paginator
    {
        $dql = "SELECT ba, b, a
            FROM App\Infrastructure\Persistence\Entity\Book b
            JOIN b.bookAuthors ba
            JOIN ba.author a
            WHERE b.imageId IS NOT null
            ORDER BY ba.id ASC";

        $query = $this->entityManager->createQuery($dql)
          ->setFirstResult($limit)
          ->setMaxResults($offset);

        return new Paginator($query);
    }

    public function getStagedBooksPaginated(int $limit, int $offset): Paginator
    {
        $dql = "SELECT ba, b, a
            FROM App\Infrastructure\Persistence\Entity\Book b
            JOIN b.bookAuthors ba
            JOIN ba.author a
            WHERE b.imageId IS null
            ORDER BY ba.id ASC";

        $query = $this->entityManager->createQuery($dql)
          ->setFirstResult($limit)
          ->setMaxResults($offset);

        return new Paginator($query);
    }

    public function delete(int $id): void
    {
        $book = $this->entityManager->getReference(Book::class, $id);

        if ($book) {
            $this->entityManager->delete($book, true);
        }
    }

    public function saveBooksFromCsv(array $entities): void
    {
        $this->entityManager->getConnection()->transactional(function () use ($entities) {
            $i = 1;
            $batchSize = 30;

            foreach ($entities as $entity){
                try {
                    $this->entityManager->persist($entity);

                    if ($i % $batchSize === 0) {
                        $this->entityManager->sync();
                        $this->entityManager->clear();
                    } else {
                        ++$i;
                    }

                } catch (UniqueConstraintViolationException $e) {
                    preg_match('/\b\d{13}\b/', $e->getMessage(), $matches);
                    throw new BookException("Book with ISBN {$matches[0]} is already exists.
                    All inserts have been canceled.");
                } catch (\Throwable $e){
                    throw $e;
                }
            }

            if ($i > 1) {
                $this->entityManager->sync();
                $this->entityManager->clear();
            }
        });
    }

}