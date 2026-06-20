<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/books', \App\Http\Controllers\ListBooksController::class);
