<?php

use App\Http\Controller\Admin\AdminController;
use App\Http\Controller\Admin\BookController;
use App\Http\Controller\HomeController;
use App\Http\Controller\LoginController;
use Igarevv\Micrame\Router\Route;

return [
  Route::get('/', [HomeController::class, 'index']),
  Route::get('/sign-up', [LoginController::class, 'signUpIndex']),
  Route::post('/sign-up', [LoginController::class, 'register']),
  Route::get('/sign-in', [LoginController::class, 'signInIndex']),
  Route::post('/sign-in', [LoginController::class, 'login']),
  Route::get('/admin/add-book', [AdminController::class, 'showBookForm']),
  Route::post('/admin/add-book', [BookController::class, 'add']),
  Route::get('/admin/main', [AdminController::class, 'index']),
  Route::get('/admin/list', [AdminController::class, 'showBookList']),
  Route::delete('/admin/book/{id}', [BookController::class, 'delete']),
];