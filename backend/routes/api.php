<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListBooksController;
use App\Http\Controllers\ListAuthorsController;
use App\Http\Controllers\ListSubjectsController;

Route::get('/books', ListBooksController::class);
Route::get('/authors', ListAuthorsController::class);
Route::get('/subjects', ListSubjectsController::class);
