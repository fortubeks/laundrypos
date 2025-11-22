<?php

use App\Http\Controllers\ServiceItemController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'service-items', 'middleware' => ['auth:sanctum']], function () {

    Route::get('/{id}', [ServiceItemController::class, 'get_service_item']);
    Route::get('/', [ServiceItemController::class, 'get_service_items']);
    Route::post('/', [ServiceItemController::class, 'create_service_item']);
    Route::put('/{id}', [ServiceItemController::class, 'update_service_item']);
    Route::delete('/{id}', [ServiceItemController::class, 'delete_service_item']);

});
