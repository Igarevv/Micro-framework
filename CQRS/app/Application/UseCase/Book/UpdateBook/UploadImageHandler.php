<?php

namespace App\Application\UseCase\Book\UpdateBook;

use App\Domain\Based\Bus\Command\CommandHandlerInterface;
use App\Domain\Based\Bus\Command\CommandInterface;
use App\Domain\Book\Exception\BookException;
use App\Domain\Book\Repository\BookManagementRepositoryInterface;
use App\Infrastructure\Services\ImageService;

class UploadImageHandler implements CommandHandlerInterface
{
    public function __construct(
      private readonly BookManagementRepositoryInterface $repository,
    ) {}

    /**
     * @param  UploadImageCommand  $command
     *
     * @throws \App\Domain\Book\Exception\BookException
     */
    public function handle(CommandInterface $command): void
    {
        try {
            $this->repository->updateBookImageData($command->getBookId(),$command->getImageId());
        } catch (\Throwable $e){
            throw new BookException("Exception on updating book info.
            Contact to your stupid developer.");
        }
    }

}