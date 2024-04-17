<?php

namespace App\Controller;

use Igarevv\Micrame\Controller\Controller;
use Igarevv\Micrame\Http\Response;

class LoginController extends Controller
{
    public function signUpIndex(): Response
    {
        return $this->render('register.html.twig');
    }

    public function register(): Response
    {
        dd($this->request());
    }
}
