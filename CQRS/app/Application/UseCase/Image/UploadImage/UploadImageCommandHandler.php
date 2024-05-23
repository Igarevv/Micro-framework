<?php

namespace App\Application\UseCase\Image\UploadImage;

use App\Domain\Based\Bus\Command\CommandHandlerInterface;
use App\Domain\Based\Bus\Command\CommandInterface;
use App\Domain\Book\Exception\CloudinaryException;
use App\Domain\Book\Repository\ImageRepositoryInterface;

class UploadImageCommandHandler implements CommandHandlerInterface
{

    public function __construct(
      private readonly ImageRepositoryInterface $repository
    ) {}

    /**
     * @param  UploadImageCommand  $command
     *
     * @throws \App\Domain\Book\Exception\CloudinaryException
     */
    public function handle(CommandInterface $command): bool
    {
        try {
            $this->repository->upload($command->getImageData());
        } catch (\Throwable $e){
            throw CloudinaryException::cloudinaryServiceError();
        }

        return true;
    }

}