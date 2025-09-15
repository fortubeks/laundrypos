<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    //Dashboard
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    //Orders
    Route::resource('orders', App\Http\Controllers\OrderController::class);

    //Customers
    Route::resource('customers', App\Http\Controllers\CustomerController::class);

    //Service items
    Route::resource('service-items', App\Http\Controllers\ServiceItemController::class);

    //Reports
    Route::get('/revenues', [App\Http\Controllers\DashboardController::class, 'index'])->name('reports.revenue');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
