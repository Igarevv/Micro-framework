<?php

namespace App\Http\Controller;

use App\Services\BookService;
use Igarevv\Micrame\Controller\Controller;
use Igarevv\Micrame\Http\Response\Response;

class HomeController extends Controller
{

    public function __construct(
      private BookService $bookService,
    ) {}

    public function index(): Response
    {
        $page = $this->request->getGet('page') ?: 1;
        $limit = 6;

        try {
            $books = $this->bookService->getBooksForHomePage($page, $limit);

        } catch (\Throwable $e) {
            return $this->render('home.html.twig');
        }

        return $this->render('home.html.twig', [
          'books'       => $books['collection'],
          'pagesCount'  => $books['pages'],
          'currentPage' => $page
        ]);
    }

}