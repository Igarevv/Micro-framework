<?php

namespace App\Infrastructure\Persistence\Entity;

use App\Infrastructure\Services\EntityManager\Contracts\DatabaseEntity;
use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table('book_author')]
class BookAuthor implements DatabaseEntity
{
    #[Id, Column, GeneratedValue]
    private ?int $id;

    #[ManyToOne(cascade: ['persist', 'remove'], inversedBy: 'bookAuthors')]
    private Book $book;

    #[ManyToOne(cascade: ['persist', 'remove'], inversedBy: 'bookAuthors')]
    private Author $author;

    #[Column(name: 'created_at')]
    private DateTimeImmutable $createdAt;

    public function __construct(
      Book $book,
      Author $author,
      ?int $id = null
    ) {
        $this->book = $book;
        $this->author = $author;
        $this->id = $id;
        $this->createdAt = new DateTimeImmutable();
    }

    public static function create(
      Book $book,
      Author $author
    ): static {
        return new static($book, $author);
    }

    public function addAuthor(?Author $author): void
    {
        $this->author = $author;
    }

    public function addBook(?Book $book): void
    {
        $this->book = $book;
    }

    public function getBook(): Book
    {
        return $this->book;
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }

    public function getDateTime(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

}