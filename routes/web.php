<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', fn() => view('auth.login'))->name('login');
Route::get('/register', fn() => view('auth.register'))->name('register');
Route::post('/register', [UserController::class, 'register'])->name('register');
Route::post('/login', [UserController::class, 'login'])->name('login');
Route::get('/catalog', [BookController::class, 'catalog'])->name('books.catalog');

Route::group(['middleware' => 'auth'], function () {
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
    Route::resource('carts', CartController::class);
    Route::post('/cart', [CartController::class, 'store'])->name('carts.store');
    Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::post('/buy-now', [CartController::class, 'buyNow'])->name('buy-now');

    Route::group(['middleware' => 'isAdmin'], function () {
        Route::get('/invoice', [InvoiceController::class, 'displayAllInvoice'])->name('invoice.display');
        Route::resource('books', BookController::class)->except(['show']); // ✅ Letakkan sebelum /books/{book}
    });

    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
});
