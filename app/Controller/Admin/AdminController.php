<?php

namespace App\Controller\Admin;

use App\Services\BookService;
use Igarevv\Micrame\Controller\Controller;
use Igarevv\Micrame\Http\Response\Response;

class AdminController extends Controller
{
    public function __construct(
      private BookService $service
    ) {}

    public function index(): Response
    {
        return $this->render('/admin/admin.main.twig');
    }

    public function showBookForm(): Response
    {
        return $this->render('/admin/admin.addbook.twig');
    }

    public function showBookList(): Response
    {
        $books = $this->service->getBooksForTable();
        return $this->render('/admin/admin.list.twig', ['books' => $books]);
    }
}
