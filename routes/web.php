<?php

use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('home');
});

// Route::resource('/transactions', \App\Http\Controllers\TransactionController::class);
Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
Route::get('/transactions/by-product', [TransactionController::class, 'byProduct'])->name('transactions.byproduct');