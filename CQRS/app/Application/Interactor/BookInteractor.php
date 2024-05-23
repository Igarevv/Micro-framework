<?php

namespace App\Application\Interactor;

use App\Application\UseCase\Book\CreateBook\CreateBookCommand;
use App\Application\UseCase\Book\CreateBook\CreateBookCommandHandler;
use App\Application\UseCase\Book\DeleteBook\DeleteBookCommand;
use App\Application\UseCase\Book\DeleteBook\DeleteBookCommandHandler;
use App\Application\UseCase\Book\UpdateBook\UploadImageHandler;
use App\Application\UseCase\Image\DeleteImage\DeleteImageCommand;
use App\Application\UseCase\Image\DeleteImage\DeleteImageCommandHandler;
use App\Application\UseCase\Image\UploadImage\UploadImageCommand;
use App\Application\UseCase\Image\UploadImage\UploadImageCommandHandler;
use App\Infrastructure\Bus\Command\CommandBusInterface;
use App\Infrastructure\Services\ImageService;
use App\Application\UseCase\Book\UpdateBook\UploadImageCommand as BookUploadImage;
use Exception;

class BookInteractor
{

    public function __construct(
      private readonly CommandBusInterface $commandBus,
      private readonly ImageService $imageService
    ) {}

    public function save(array $bookData, array $imageData): void
    {
        $image = $this->imageService->createUniqueImage($imageData);

        $bookId = $this->commandBus->dispatch(new CreateBookCommand($bookData,
          $image->getFileName()), CreateBookCommandHandler::class);

        $deleteCommand = new DeleteBookCommand($bookId);

        try {
            $isUpload = $this->commandBus->dispatch(new UploadImageCommand($image),
              UploadImageCommandHandler::class);

            if ( ! $isUpload) {
                $this->commandBus->dispatch($deleteCommand,
                  DeleteBookCommandHandler::class);
            }
        } catch (\Throwable $e) {
            $this->commandBus->dispatch($deleteCommand,
              DeleteBookCommandHandler::class);
            throw $e;
        }
    }

    public function delete(int $id): void
    {
        try {
            $this->commandBus->dispatch(new DeleteImageCommand($id),
              DeleteImageCommandHandler::class);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updateBookImage(int $id, array $imageData): void
    {
        $image = $this->imageService->createUniqueImage($imageData);

        try {
            $this->commandBus->dispatch(new BookUploadImage($id,
              $image->getFullName()),
              UploadImageHandler::class);

            $isUpload = $this->commandBus->dispatch(new UploadImageCommand($image),
              UploadImageCommandHandler::class);

            if (! $isUpload) {
                $this->roleBackBookImage($id);
            }
        } catch (\Throwable $e) {
            $this->roleBackBookImage($id);

            throw $e;
        }
    }

    private function roleBackBookImage(int $id): void
    {
        $this->commandBus->dispatch(new BookUploadImage($id, null),
          UploadImageHandler::class);
    }

}