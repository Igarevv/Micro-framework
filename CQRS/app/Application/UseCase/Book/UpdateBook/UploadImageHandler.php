<?php

namespace App\Application\UseCase\Book\UpdateBook;

use App\Domain\Based\Bus\Command\CommandHandlerInterface;
use App\Domain\Based\Bus\Command\CommandInterface;
use App\Domain\Book\Repository\BookRepositoryInterface;
use App\Infrastructure\Services\ImageService;

class UploadImageHandler implements CommandHandlerInterface
{
    public function __construct(
      private readonly BookRepositoryInterface $repository,
      private readonly ImageService $imageService
    ) {}

    /**
     * @param  UploadImageCommand  $command
     *
     * @throws \App\Domain\Book\Exception\ImageException
     */
    public function handle(CommandInterface $command)
    {
        $image = $this->imageService->createUniqueImage($command->getImageData());

        $this->repository->
    }

}