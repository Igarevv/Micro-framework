<?php

use App\Http\Controller\Admin\AdminController;
use App\Http\Controller\Admin\BookController;
use App\Http\Controller\HomeController;
use App\Http\Controller\LoginController;
use App\Http\Middlewares\Authenticate;
use App\Http\Middlewares\Guest;
use Igarevv\Micrame\Router\Route;

return [
  Route::get('/', [HomeController::class, 'index']),
  Route::get('/sign-up', [LoginController::class, 'signUpIndex'], [Guest::class]),
  Route::post('/sign-up', [LoginController::class, 'register']),
  Route::get('/sign-in', [LoginController::class, 'signInIndex'], [Guest::class]),
  Route::post('/sign-in', [LoginController::class, 'login']),
  Route::post('/logout', [LoginController::class, 'logout']),
  Route::get('/admin/add-book', [AdminController::class, 'showBookForm'], [Authenticate::class]),
  Route::post('/admin/add-book', [BookController::class, 'add']),
  Route::get('/admin/main', [AdminController::class, 'index']),
  Route::get('/admin/list', [AdminController::class, 'showBookList']),
  Route::delete('/admin/book/{id}', [BookController::class, 'delete']),
];