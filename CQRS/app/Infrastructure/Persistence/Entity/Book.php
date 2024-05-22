<?php

namespace App\Infrastructure\Persistence\Entity;

use App\Domain\Book\ValueObject\Isbn;
use App\Domain\Book\ValueObject\Year;
use App\Infrastructure\Services\EntityManager\Contracts\DatabaseEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table('book')]
class Book implements DatabaseEntity
{
    #[Id, Column, GeneratedValue]
    private ?int $id;

    #[Column]
    private string $title;

    #[Column(type: 'Year')]
    private Year $year;

    #[Column]
    private string $description;

    #[Column(name: 'image_cdn_id')]
    private ?string $imageId;

    #[Column(type: Types::JSON)]
    private array $genre;

    #[Column(type: 'Isbn')]
    private Isbn $isbn;

    #[OneToMany(targetEntity: BookAuthor::class, mappedBy: 'book')]
    private Collection $bookAuthors;

    public function __construct(
      ?int $id,
      string $title,
      Year $year,
      string $description,
      array $genre,
      Isbn $isbn,
      ?string $imageId = null,
    )
    {
        $this->id = $id;
        $this->bookAuthors = new ArrayCollection();
        $this->title = $title;
        $this->year = $year;
        $this->description = $description;
        $this->imageId = $imageId;
        $this->genre = $genre;
        $this->isbn = $isbn;
    }

    public static function create(
      string $title,
      Year $year,
      string $description,
      array $genre,
      Isbn $isbn,
      ?int $id = null,
      ?string $imageId = null,
    ): static
    {
        return new static($id, $title, $year, $description, $genre, $isbn, $imageId);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<int, BookAuthor>
     */
    public function getBookAuthors(): Collection
    {
        return $this->bookAuthors;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getYear(): Year
    {
        return $this->year;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getImageId(): ?string
    {
        return $this->imageId;
    }

    public function getGenre(): array
    {
        return $this->genre;
    }

    public function getIsbn(): Isbn
    {
        return $this->isbn;
    }

    public function addBookAuthor(BookAuthor $bookAuthor): void
    {
        if (! $this->bookAuthors->contains($bookAuthor)){
            $this->bookAuthors->add($bookAuthor);
            $bookAuthor->addBook($this);
        }
    }

    public function removeBookAuthor(BookAuthor $bookAuthor): void
    {
        if ($bookAuthor->getBook() === $this){
            $this->bookAuthors->removeElement($bookAuthor);
            $bookAuthor->addBook(null);
        }
    }

}