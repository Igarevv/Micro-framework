<?php

namespace App\Application\UseCase\Book\GetBooksPaginated\Home;

use App\Application\Mappers\BookMapper;
use App\Application\Presenter\BookPagePresenter;
use App\Domain\Based\Bus\Query\QueryHandleInterface;
use App\Domain\Based\Bus\Query\QueryInterface;
use App\Domain\Book\Exception\BookException;
use App\Domain\Book\Repository\BookManagementRepositoryInterface;
use App\Domain\Book\Repository\ImageRepositoryInterface;
use App\Domain\Book\ValueObject\Title;
use App\Infrastructure\Persistence\Entity\Book;
use Doctrine\ORM\NoResultException;

class GetFullBookDataQueryHandler implements QueryHandleInterface
{

    public function __construct(
      private readonly BookManagementRepositoryInterface $bookRepository,
      private readonly ImageRepositoryInterface $imageRepository,
      private readonly BookMapper $bookMapper
    ) {}

    /**
     * @param  GetFullBookDataQuery  $command
     *
     * @throws \App\Domain\Book\Exception\BookException
     */
    public function handle(QueryInterface $command): \stdClass
    {
        try {
            $book = $this->bookRepository->getOneForBookPage($command->getBookId());
        } catch (NoResultException $e){
            throw new BookException('Book not found', 404);
        }

        return $this->makePresentation($book);
    }

    public function makePresentation(Book $entity): \stdClass
    {
        $bookDomain = $this->bookMapper->toDomain($entity);

        $imageUrl = $this->imageRepository->getOneImage($bookDomain->getImage());

        if (! $imageUrl){
            throw new BookException('Image for book not found. Please send this message to contact center');
        }

        $bookDomain->setHttpImageUrl($imageUrl);

        return (new BookPagePresenter($bookDomain))->toBase();
    }

}