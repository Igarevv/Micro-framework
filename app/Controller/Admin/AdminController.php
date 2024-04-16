<?php

namespace App\Controller\Admin;

use Igarevv\Micrame\Controller\Controller;
use Igarevv\Micrame\Http\Response;

class AdminController extends Controller
{
    public function index(): Response
    {
        return $this->render('/admin/admin.main.twig');
    }

    public function showBookForm(): Response
    {
        return $this->render('/admin/admin.addbook.twig');
    }
}
