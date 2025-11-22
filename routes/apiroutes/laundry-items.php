<?php

use App\Http\Controllers\LaundryItemController;
use App\Http\Controllers\ServiceItemController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'laundry-items', 'middleware' => ['auth:sanctum']], function () {

    Route::get('/{id}', [LaundryItemController::class, 'get_laundry_item']);
    Route::get('/', [LaundryItemController::class, 'get_laundry_items']);
    Route::post('/', [LaundryItemController::class, 'create_laundry_item']);
    Route::put('/{id}', [LaundryItemController::class, 'update_laundry_item']);
    Route::delete('/{id}', [LaundryItemController::class, 'delete_laundry_item']);

});
