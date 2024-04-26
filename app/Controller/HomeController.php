<?php

namespace App\Controller;

use App\Services\BookService;
use App\Services\ImageService;
use Igarevv\Micrame\Controller\Controller;
use Igarevv\Micrame\Http\Response\Response;

class HomeController extends Controller
{

    public function __construct(
      private BookService $bookService,
    ) {}

    public function index(): Response
    {
        try {
            $books = $this->bookService->getBookForHomePage();

        } catch (\Throwable $e){
            return $this->render('home.html.twig');
        }

        return $this->render('home.html.twig', ['books' => $books]);
    }

}