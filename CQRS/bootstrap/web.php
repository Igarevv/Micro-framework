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
  Route::get('/book/{bookUrlId}', [HomeController::class, 'getOneBook']),
  Route::get('/sign-up', [AuthController::class, 'signUpIndex'], [Guest::class]),
  Route::post('/sign-up', [AuthController::class, 'register'], [Guest::class]),
  Route::get('/sign-in', [AuthController::class, 'signInIndex'], [Guest::class]),
  Route::post('/sign-in', [AuthController::class, 'login'], [Guest::class]),
  Route::post('/logout', [AuthController::class, 'logout'], [Authenticate::class]),
  Route::get('/admin/book', [AdminController::class, 'showBookForm'], [Authenticate::class]),
  Route::post('/admin/book/add', [BookController::class, 'save'], [Authenticate::class]),
  Route::get('/admin/list', [AdminController::class, 'showTable'], [Authenticate::class]),
  Route::delete('/admin/book/{id}', [BookController::class, 'delete'], [Authenticate::class]),
  Route::get('/admin/book/unready', [AdminController::class, 'showUnreadyBooks'], [Authenticate::class]),
  Route::post('/admin/book/add/csv', [BookController::class, 'saveCsv'], [Authenticate::class]),
  Route::get('/admin/home', [AdminController::class, 'index'], [Authenticate::class]),
  Route::post('/admin/book/unready', [BookController::class, 'uploadImage'], [Authenticate::class])
];