<?php

namespace App\Presentation\Controllers;

use App\Application\Interactor\BookInteractor;
use App\Application\UseCase\Book\SaveCSV\SaveCsvBookCommand;
use App\Application\UseCase\Book\SaveCSV\SaveCsvBookHandler;
use App\Domain\Based\Exception\InvalidFormat;
use App\Domain\Book\Exception\BookException;
use App\Domain\Book\Exception\CloudinaryException;
use App\Domain\Book\Exception\ImageException;
use App\Infrastructure\Bus\Command\CommandBus;
use App\Infrastructure\Bus\Command\CommandBusInterface;
use App\Infrastructure\Services\Session\FlashMessageHandler;
use Exception;
use Igarevv\Micrame\Controller\Controller;
use Igarevv\Micrame\Exceptions\Http\HttpException;
use Igarevv\Micrame\Http\Response\JsonResponse;
use Igarevv\Micrame\Http\Response\RedirectResponse;
use Igarevv\Micrame\Http\Response\Response;
use Igarevv\Micrame\Session\SessionInterface;
use Psr\Http\Message\UploadedFileInterface;

class BookController extends Controller
{

    public function __construct(
      private readonly BookInteractor $interactor,
      private readonly CommandBusInterface $commandBus,
      private readonly FlashMessageHandler $flasher
    ) {}

    public function save(): Response
    {
        $bookData = $this->getMergedBookData();
        $imageData = $this->request->getFile('image');

        try {
            $this->interactor->save($bookData, $imageData);
        } catch (InvalidFormat|ImageException|BookException|CloudinaryException $e) {
            $this->flasher->setError('error', $e->getMessage(),
              ['data' => $bookData]);

            return new RedirectResponse('/admin/book');
        }
        $this->flasher->setSuccess('success-full-add',
          'Book was successfully added');

        return new RedirectResponse('/admin/list');
    }

    public function delete(int $id): Response
    {
        try {
            $this->interactor->delete($id);
        } catch (Exception $e) {
            $this->flasher->setError('error',
              'Error deleting book: '.$e->getMessage());

            return new JsonResponse(['error' => $e->getMessage()]);
        }

        return new JsonResponse();
    }

    public function saveCsv(): Response
    {
        try {
            $fileData = $this->request->getFiles('csv');

            $this->commandBus->dispatch(new SaveCsvBookCommand($fileData),
              SaveCsvBookHandler::class);
        } catch (InvalidFormat|HttpException|BookException $e) {
            $this->flasher->setError('errorCsv', $e->getMessage());

            return new RedirectResponse('/admin/book');
        }
        $this->flasher->setSuccess('success', 'Books was successfully added');

        return new RedirectResponse('/admin/book/unready');
    }

    public function uploadImage(): Response
    {
        $bookId = $this->request->getPost('bookId');
        $image = $this->request->getFile('image');

        try {
            $this->interactor->updateBookImage($bookId, $image);
        } catch (\Throwable $e) {
            if ($e instanceof ImageException) {
                $this->flasher->setError('error-upload', $e->getMessage());
            } else {
                $this->flasher->setError('error-upload', 'Internal Error');
            }
            return new JsonResponse([
              'status' => 500, 'redirect' => '/admin/book/unready',
            ]);
        }
        $this->flasher->setSuccess('success-upload-add',
          "Image for book №{$bookId} successfully uploaded");

        return new JsonResponse(['status' => 200, 'redirect' => '/admin/list']);
    }

    private function getMergedBookData(): array
    {
        $authorData = $this->request->getPost(['first_name', 'last_name']);

        $bookData = $this->request->getPost([
          'title', 'year', 'genre', 'description', 'isbn',
        ]);

        return array_merge($bookData, $authorData);
    }

}