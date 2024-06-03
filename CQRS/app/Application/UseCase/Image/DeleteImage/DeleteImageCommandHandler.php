<?php

namespace App\Application\UseCase\Image\DeleteImage;

use App\Domain\Based\Bus\Command\CommandHandlerInterface;
use App\Domain\Based\Bus\Command\CommandInterface;
use App\Domain\Book\Exception\BookException;
use App\Domain\Book\Repository\BookManagementRepositoryInterface;
use App\Domain\Book\Repository\ImageRepositoryInterface;

class DeleteImageCommandHandler implements CommandHandlerInterface
{

    public function __construct(
      private readonly ImageRepositoryInterface $imageRepository,
      private readonly BookManagementRepositoryInterface $bookRepository
    ) {}

    /**
     * @param  DeleteImageCommand  $command
     *
     * @throws \App\Domain\Book\Exception\BookException
     */
    public function handle(CommandInterface $command): void
    {
        $book = $this->bookRepository->getById($command->getId());

        if (! $book){
            throw BookException::booksNotFound();
        }

        $this->bookRepository->delete($command->getId());

        if ($book->getImageId()){
            $this->imageRepository->delete($book->getImageId());
        }
    }

}