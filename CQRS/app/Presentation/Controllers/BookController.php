<?php

namespace App\Presentation\Controllers;

use App\Application\Interactor\BookInteractor;
use App\Domain\Based\Exception\InvalidFormat;
use App\Domain\Book\Exception\BookException;
use App\Domain\Book\Exception\CloudinaryException;
use App\Domain\Book\Exception\ImageException;
use Exception;
use Igarevv\Micrame\Controller\Controller;
use Igarevv\Micrame\Http\Response\JsonResponse;
use Igarevv\Micrame\Http\Response\RedirectResponse;
use Igarevv\Micrame\Http\Response\Response;

class BookController extends Controller
{

    public function __construct(
      private readonly BookInteractor $interactor
    ) {}

    public function save(): Response
    {
        $bookData = $this->getMergedBookData();
        $imageData = $this->request->getFiles('image');

        try {
            $this->interactor->save($bookData, $imageData);
        } catch (InvalidFormat|ImageException|BookException|CloudinaryException $e){
            $this->request->session()->setFlash('error',[
              'error' => $e->getMessage(),
              'data'  => $bookData,
            ]);
            return new RedirectResponse('/admin/add-book');
        }

        $this->request
          ->session()
          ->setFlash('success', 'Book was successfully added');
        return new RedirectResponse('/admin/list');
    }

    public function delete(int $id): Response
    {
        try {
            $this->interactor->delete($id);
        } catch (Exception $e){
            $this->request->session()->setFlash('error',[
              'error' => 'Error deleting book: '.$e->getMessage()
            ]);

            return new JsonResponse(['error' => $e->getMessage()]);
        }

        return new JsonResponse();
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