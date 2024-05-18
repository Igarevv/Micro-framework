<?php

use App\Presentation\Controllers\AdminController;
use App\Presentation\Controllers\AuthController;
use App\Presentation\Controllers\BookController;
use App\Presentation\Controllers\HomeController;
use App\Presentation\Middlewares\Authenticate;
use App\Presentation\Middlewares\Guest;
use Igarevv\Micrame\Router\Route;

return [
  Route::get('/', [HomeController::class, 'index']),
  Route::get('/sign-up', [AuthController::class, 'signUpIndex'], [Guest::class]),
  Route::post('/sign-up', [AuthController::class, 'register']),
  Route::get('/sign-in', [AuthController::class, 'signInIndex'], [Guest::class]),
  Route::post('/sign-in', [AuthController::class, 'login']),
  Route::post('/logout', [AuthController::class, 'logout']),
  Route::get('/admin/add-book', [AdminController::class, 'showBookForm'], [Authenticate::class]),
  Route::post('/admin/add-book', [BookController::class, 'save']),
  Route::get('/admin/main', [AdminController::class, 'index']),
  Route::get('/admin/list', [AdminController::class, 'showTable']),
  Route::delete('/admin/book/{id}', [BookController::class, 'delete']),
];