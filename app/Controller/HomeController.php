<?php

namespace App\Controller;

use Igarevv\Micrame\Controller\Controller;
use Igarevv\Micrame\Http\Response;

class HomeController extends Controller
{

    public function index(): Response
    {
        return $this->render('home.html.twig');
    }

}