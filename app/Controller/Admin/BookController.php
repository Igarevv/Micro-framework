<?php

namespace App\Controller\Admin;

use App\Entities\Author;
use App\Entities\Book;
use App\Services\BookService;
use App\Services\ImageService;
use Igarevv\Micrame\Controller\Controller;
use Igarevv\Micrame\Http\Response;

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
          'title', 'year', 'genre', 'description',
        ]);
        $imageData = $this->request->getFiles('image');

        try {
            $image = $this->imageService->image($imageData);

            $bookCollection = $this->bookService->createBook($bookData,
              $authorData, $image);

            $this->bookService->save($bookCollection);


        } catch (\Exception $e) {
            throw $e;
        }

        return new Response();
    }

}