<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListBooksController;
use App\Http\Controllers\ListAuthorsController;
use App\Http\Controllers\ListSubjectsController;
use App\Http\Controllers\GetAllAuthorsController;
use App\Http\Controllers\GetAllSubjectsController;
use App\Http\Controllers\StoreBookController;
use App\Http\Controllers\UpdateBookController;
use App\Http\Controllers\GetBookByIdController;

Route::get('/books', ListBooksController::class);
Route::post('/books', StoreBookController::class);
Route::get('/books/{id}', GetBookByIdController::class)->where('id', '[0-9]+');
Route::put('/books/{id}', UpdateBookController::class)->where('id', '[0-9]+');

Route::get('/authors/all', GetAllAuthorsController::class);
Route::get('/authors', ListAuthorsController::class);
Route::get('/subjects/all', GetAllSubjectsController::class);
Route::get('/subjects', ListSubjectsController::class);
