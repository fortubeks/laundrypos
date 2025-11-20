<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'user', 'middleware' => ['auth:sanctum']], function () {

    Route::get('/', [UserController::class, 'get_user']);
    Route::put('/', [UserController::class, 'updateProfile']);
    Route::put('/change-password', [UserController::class, 'changePassword']);
    Route::delete('/', [UserController::class, 'delete_user']);

});
