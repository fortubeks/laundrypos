<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'dashboard', 'middleware' => ['auth:sanctum']], function () {

    Route::get('/', [DashboardController::class, 'get_analytics']);

});
