<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'orders-service-items', 'middleware' => ['auth:sanctum']], function () {

    Route::get('/{id}', [OrderController::class, 'get_order_service_item']);
    Route::get('/', [OrderController::class, 'get_orders_service_items']);
    Route::post('/', [OrderController::class, 'create_order_service_item']);
    Route::put('/{id}', [OrderController::class, 'update_order_service_item']);
    Route::delete('/{id}', [OrderController::class, 'delete_order_service_item']);

});
