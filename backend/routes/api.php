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
use App\Http\Controllers\DeleteBookController;
use App\Http\Controllers\StoreAuthorController;
use App\Http\Controllers\UpdateAuthorController;
use App\Http\Controllers\GetAuthorByIdController;
use App\Http\Controllers\DeleteAuthorController;
use App\Http\Controllers\StoreSubjectController;
use App\Http\Controllers\UpdateSubjectController;
use App\Http\Controllers\GetSubjectByIdController;
use App\Http\Controllers\DeleteSubjectController;
use App\Http\Controllers\ReportBookController;
use App\Http\Controllers\ReportAuditController;

Route::get('/reports/books', ReportBookController::class);
Route::get('/reports/audits', ReportAuditController::class);

Route::get('/books', ListBooksController::class);
Route::post('/books', StoreBookController::class);
Route::get('/books/{id}', GetBookByIdController::class)->where('id', '[0-9]+');
Route::put('/books/{id}', UpdateBookController::class)->where('id', '[0-9]+');
Route::delete('/books/{id}', DeleteBookController::class)->where('id', '[0-9]+');

Route::get('/authors/all', GetAllAuthorsController::class);
Route::get('/authors', ListAuthorsController::class);
Route::post('/authors', StoreAuthorController::class);
Route::get('/authors/{id}', GetAuthorByIdController::class)->where('id', '[0-9]+');
Route::put('/authors/{id}', UpdateAuthorController::class)->where('id', '[0-9]+');
Route::delete('/authors/{id}', DeleteAuthorController::class)->where('id', '[0-9]+');

Route::get('/subjects/all', GetAllSubjectsController::class);
Route::get('/subjects', ListSubjectsController::class);
Route::post('/subjects', StoreSubjectController::class);
Route::get('/subjects/{id}', GetSubjectByIdController::class)->where('id', '[0-9]+');
Route::put('/subjects/{id}', UpdateSubjectController::class)->where('id', '[0-9]+');
Route::delete('/subjects/{id}', DeleteSubjectController::class)->where('id', '[0-9]+');
