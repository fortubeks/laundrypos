<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'orders', 'middleware' => ['auth:sanctum']], function () {

    Route::get('/', [OrderController::class, 'get_orders']);
    Route::post('/', [OrderController::class, 'create_order']);
    Route::put('/{id}', [OrderController::class, 'update_order']);
    Route::delete('/{id}', [OrderController::class, 'delete_order']);
    Route::get('/{id}', [OrderController::class, 'get_order']);

});
