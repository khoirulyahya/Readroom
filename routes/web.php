<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; //inisialisasi
// use App\Http\Controllers\PublisherController;
// use App\Http\Controllers\CatalogController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index']);
// Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index']);
Route::get('test_spatie', [App\Http\Controllers\HomeController::class, 'test_spatie']);

Route::get('/print_bill/{id}', [App\Http\Controllers\TransactionController::class, 'print']);
// Route::get('/transactions', [App\Http\Controllers\TransactionController::class, 'index']);

Route::resource('transactions', App\Http\Controllers\TransactionController::class);
Route::resource('transaction/details', App\Http\Controllers\TransactionDetailController::class);

Route::resource('authors', App\Http\Controllers\AuthorController::class);
// Route::get('/authors', [App\Http\Controllers\AuthorController::class, 'index']);
// Route::get('/print_author/', [App\Http\Controllers\AuthorController::class, 'print']);

Route::resource('books', App\Http\Controllers\BookController::class);


Route::resource('members', App\Http\Controllers\MemberController::class);
// Route::get('/members', [App\Http\Controllers\MemberController::class, 'index']);

Route::resource('catalogs', App\Http\Controllers\CatalogController::class);

// Route::get('/catalogs', [App\Http\Controllers\CatalogController::class, 'index']);
// Route::get('/catalogs/create', [App\Http\Controllers\CatalogController::class, 'create']);
// Route::post('/catalogs', [App\Http\Controllers\CatalogController::class, 'store']);
// Route::get('/catalogs/{catalog}/edit', [App\Http\Controllers\CatalogController::class, 'edit']);
// Route::put('/catalogs/{catalog}', [App\Http\Controllers\CatalogController::class, 'update']);
// Route::delete('/catalogs/{catalog}', [App\Http\Controllers\CatalogController::class, 'destroy']);


Route::resource('publishers', App\Http\Controllers\PublisherController::class);

// Route::get('/publishers', [App\Http\Controllers\PublisherController::class, 'index']);
// Route::get('/publishers/create', [App\Http\Controllers\PublisherController::class, 'create']);
// Route::post('/publishers', [App\Http\Controllers\PublisherController::class, 'store']);
// Route::get('/publishers/{publisher}/edit', [App\Http\Controllers\PublisherController::class, 'edit']);
// Route::put('/publishers/{publisher}', [App\Http\Controllers\PublisherController::class, 'update']);
// Route::delete('/publishers/{publisher}', [App\Http\Controllers\PublisherController::class, 'destroy']);

Route::get('/api/authors', [App\Http\Controllers\AuthorController::class, 'api']);
Route::get('/api/publishers', [App\Http\Controllers\PublisherController::class, 'api']);
Route::get('/api/members', [App\Http\Controllers\MemberController::class, 'api']);
Route::get('/api/books', [App\Http\Controllers\BookController::class, 'api']);
Route::get('/api/transactions', [App\Http\Controllers\TransactionController::class, 'api']);
