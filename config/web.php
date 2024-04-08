<?php

use App\Controller\HomeController;
use Igarevv\Micrame\Router\Route;

return [
  Route::get('/', [HomeController::class, 'index'])
];