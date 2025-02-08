<?php

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Auth\LoginRegisterController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware('auth')->group(function () {
    Route::get('/home', [LoginRegisterController::class, 'home'])->name('home');
    Route::post('/logout', [LoginRegisterController::class, 'logout'])->name('logout');
// Route::resource('/transactions', \App\Http\Controllers\TransactionController::class);
Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
Route::get('/transactions/by-product', [TransactionController::class, 'byProduct'])->name('transactions.byproduct');
Route::get('/transactions/by-device', [TransactionController::class, 'byDevice'])->name('transactions.bydevice');
Route::get('/transactions/detail', [TransactionController::class, 'transactionDetail'])->name('transactions.detail');
Route::get('/transactions/detail-device', [TransactionController::class, 'transactionDetailDevice'])->name('transactions.detaildevice');
Route::get('/transactions/detail-payment', [TransactionController::class, 'transactionDetailPayment'])->name('transactions.detailpayment');
Route::get('/transactions/by-payment-method', [TransactionController::class, 'byPaymentMethod'])->name('transactions.bypaymentmethod');
});

Route::controller(LoginRegisterController::class)->group(function() {
    // Route::get('/register', 'register')->name('register');
    // Route::post('/store', 'store')->name('store');
    Route::get('/login', 'login')->name('login');
    Route::post('/authenticate', 'authenticate')->name('authenticate');
    // Route::get('/home', 'home')->name('home');
    // Route::post('/logout', 'logout')->name('logout');
});
