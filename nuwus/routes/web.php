<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit')->middleware('throttle:10,1');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('admin')->name('admin.')->middleware('api.auth')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::resource('produk', ProductController::class)
        ->except('show')->parameters(['produk' => 'id'])->names('products');
    Route::resource('mitra', PartnerController::class)
        ->except('show')->parameters(['mitra' => 'id'])->names('partners');
});
