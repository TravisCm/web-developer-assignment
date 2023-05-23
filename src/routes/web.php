<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\Http\Controllers\BooksController;

Route::delete('/books/{id}', [BooksController::class, 'destroy'])->name('books.destroy');
Route::put('/books/{id}', [BooksController::class, 'update'])->name('books.update');
Route::get('/books', [BooksController::class, 'index'])->name('books');
Route::post('/books', [BooksController::class, 'store'])->name('books.store');






