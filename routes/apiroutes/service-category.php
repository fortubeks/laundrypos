<?php

use App\Http\Controllers\ServiceCategoryController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'service-categories', 'middleware' => ['auth:sanctum']], function () {

    Route::get('/{id}', [ServiceCategoryController::class, 'get_service_category']);
    Route::get('/', [ServiceCategoryController::class, 'get_service_categories']);
    Route::post('/', [ServiceCategoryController::class, 'create_service_category']);
    Route::put('/{id}', [ServiceCategoryController::class, 'update_service_category']);
    Route::delete('/{id}', [ServiceCategoryController::class, 'delete_service_category']);

});
