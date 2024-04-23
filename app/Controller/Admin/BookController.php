<?php

namespace App\Controller\Admin;

use App\Services\BookService;
use App\Services\ImageService;
use Igarevv\Micrame\Controller\Controller;
use Igarevv\Micrame\Http\Response;
use Igarevv\Micrame\Http\RedirectResponse;

class BookController extends Controller
{

    public function __construct(
      private BookService $bookService,
      private ImageService $imageService
    ) {}

    public function add(): Response
    {
        $authorData = $this->request->getPost(['first_name', 'last_name']);
        $bookData = $this->request->getPost([
          'title', 'year', 'genre', 'description', 'isbn',
        ]);
        $imageData = $this->request->getFiles('image');

        try {
            $image = $this->imageService->imageDto($imageData);

            $bookCollection = $this->bookService->createFullBookEntity($bookData,
              $authorData, $image);

            $this->bookService->save($bookCollection);
            $this->imageService->uploadImage($image);
        } catch (\Exception $e) {
            $this->session->setFlash('error', "Error: {$e->getMessage()}");
            return new RedirectResponse('/admin/add-book');
        }

        $this->session->setFlash('success', 'Book was successfully added');

        return new RedirectResponse('/admin/list');
    }

    public function delete(int $id): Response
    {
        try {
            $this->bookService->deleteBook($id, $this->imageService);
        } catch (\Throwable $e) {
            return new RedirectResponse('/admin/list');
        }

        return new Response();
    }

}