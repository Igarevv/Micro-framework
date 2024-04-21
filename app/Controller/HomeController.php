<?php

namespace App\Controller;

use App\Services\BookService;
use App\Services\ImageService;
use Igarevv\Micrame\Controller\Controller;
use Igarevv\Micrame\Http\Response;

class HomeController extends Controller
{

    public function __construct(
      private BookService $bookService,
      private ImageService $imageService
    ) {}

    public function index(): Response
    {
        try {
            $books = $this->bookService->getBookForHomePage($this->imageService);

        } catch (\Throwable $e){
            return $this->render('home.html.twig');
        }

        return $this->render('home.html.twig', ['books' => $books]);
    }

}