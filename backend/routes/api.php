<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Books\ListBooksController;
use App\Http\Controllers\Authors\ListAuthorsController;
use App\Http\Controllers\Subjects\ListSubjectsController;
use App\Http\Controllers\Authors\GetAllAuthorsController;
use App\Http\Controllers\Subjects\GetAllSubjectsController;
use App\Http\Controllers\Books\StoreBookController;
use App\Http\Controllers\Books\UpdateBookController;
use App\Http\Controllers\Books\GetBookByIdController;
use App\Http\Controllers\Books\DeleteBookController;
use App\Http\Controllers\Authors\StoreAuthorController;
use App\Http\Controllers\Authors\UpdateAuthorController;
use App\Http\Controllers\Authors\GetAuthorByIdController;
use App\Http\Controllers\Authors\DeleteAuthorController;
use App\Http\Controllers\Subjects\StoreSubjectController;
use App\Http\Controllers\Subjects\UpdateSubjectController;
use App\Http\Controllers\Subjects\GetSubjectByIdController;
use App\Http\Controllers\Subjects\DeleteSubjectController;
use App\Http\Controllers\Reports\ReportBookController;
use App\Http\Controllers\Reports\ReportAuditController;
use App\Http\Controllers\Isbn\GetIsbnController;

Route::prefix('reports')->group(function () {
    Route::get('/books', ReportBookController::class);
    Route::get('/audits', ReportAuditController::class);
});

Route::prefix('books')->group(function () {
    Route::get('/', ListBooksController::class);
    Route::post('/', StoreBookController::class);
    
    Route::prefix('{id}')->where(['id' => '[0-9]+'])->group(function () {
        Route::get('/', GetBookByIdController::class);
        Route::put('/', UpdateBookController::class);
        Route::delete('/', DeleteBookController::class);
    });
});

Route::prefix('authors')->group(function () {
    Route::get('/all', GetAllAuthorsController::class);
    Route::get('/', ListAuthorsController::class);
    Route::post('/', StoreAuthorController::class);
    
    Route::prefix('{id}')->where(['id' => '[0-9]+'])->group(function () {
        Route::get('/', GetAuthorByIdController::class);
        Route::put('/', UpdateAuthorController::class);
        Route::delete('/', DeleteAuthorController::class);
    });
});

Route::prefix('subjects')->group(function () {
    Route::get('/all', GetAllSubjectsController::class);
    Route::get('/', ListSubjectsController::class);
    Route::post('/', StoreSubjectController::class);
    
    Route::prefix('{id}')->where(['id' => '[0-9]+'])->group(function () {
        Route::get('/', GetSubjectByIdController::class);
        Route::put('/', UpdateSubjectController::class);
        Route::delete('/', DeleteSubjectController::class);
    });
});

Route::prefix('isbn')->group(function () {
    Route::get('/{isbn}', GetIsbnController::class);
});
