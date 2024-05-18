<?php

namespace App\Http\Controller\Admin;

use App\Services\BookService;
use Igarevv\Micrame\Controller\Controller;
use Igarevv\Micrame\Http\Response\RedirectResponse;
use Igarevv\Micrame\Http\Response\Response;

class BookController extends Controller
{

    public function __construct(
      private BookService $bookService
    ) {}

    public function add(): Response
    {
        [$authorData, $bookData, $imageData] = $this->getRequestDataForBook();

        $image = $this->bookService->imageDto($imageData);
        $book  = $this->bookService->bookDto($bookData, $image->getFileName());
        $author = $this->bookService->authorDto($authorData);

        $bookCollection = $this->bookService->createFullBookEntity($book, $author);

        try {
            $this->bookService->save($bookCollection, $image);
        } catch (\Exception $e) {
            $this->request
              ->session()
              ->setFlash('error', ['error' => $e->getMessage()]);

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
            $this->bookService->deleteBook($id);
        } catch (\Throwable $e) {
            return new RedirectResponse('/admin/list');
        }

        return new Response();
    }

    private function getRequestDataForBook(): array
    {
        $authorData = $this->request->getPost(['first_name', 'last_name']);
        $bookData = $this->request->getPost([
          'title', 'year', 'genre', 'description', 'isbn',
        ]);
        $imageData = $this->request->getFiles('image');
        return [$authorData, $bookData, $imageData];
    }

}