<?php

namespace App\Infrastructure\Persistence\Entity;

use App\Domain\Based\ValueObject\FirstName;
use App\Domain\Based\ValueObject\LastName;
use App\Infrastructure\Services\EntityManager\Contracts\DatabaseEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table('author')]
class Author implements DatabaseEntity
{
    #[Id, Column, GeneratedValue]
    private ?int $id;

    #[Column(name: 'first_name', type: 'FirstName')]
    private FirstName $firstName;

    #[Column(name: 'last_name', type: 'LastName')]
    private LastName $lastName;

    #[OneToMany(targetEntity: BookAuthor::class, mappedBy: 'author')]
    private Collection $bookAuthors;

    public function __construct(
      ?int $id,
      FirstName $firstName,
      LastName $lastName,
    )
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->bookAuthors = new ArrayCollection();
    }

    public static function create(
      FirstName $firstName,
      LastName $lastName,
      ?int $id = null
    ): static
    {
        return new static($id, $firstName, $lastName);
    }

    public function getFirstName(): FirstName
    {
        return $this->firstName;
    }

    public function getLastName(): LastName
    {
        return $this->lastName;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function addBookAuthor(BookAuthor $bookAuthor): void
    {
        if (! $this->bookAuthors->contains($bookAuthor)){
            $this->bookAuthors->add($bookAuthor);
            $bookAuthor->addAuthor($this);
        }
    }

    public function removeBookAuthor(BookAuthor $bookAuthor): void
    {
        if ($bookAuthor->getAuthor() === $this){
            $this->bookAuthors->removeElement($bookAuthor);
            $bookAuthor->addAuthor(null);
        }
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<int, BookAuthor>
     */
    public function getBookAuthors(): Collection
    {
        return $this->bookAuthors;
    }

    public function getFullName(): string
    {
        return "{$this->firstName->value()} {$this->lastName->value()}";
    }

}