<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'customers', 'middleware' => ['auth:sanctum']], function () {

    Route::get('/', [CustomerController::class, 'get_customers']);
    Route::post('/', [CustomerController::class, 'create_customer']);
    Route::put('/{id}', [CustomerController::class, 'update_customer']);
    Route::delete('/{id}', [CustomerController::class, 'delete_customer']);

    Route::get('countries', [CustomerController::class, 'get_countries']);
    Route::get('states/{country_id}', [CustomerController::class, 'get_states']);
    Route::get('/{id}', [CustomerController::class, 'get_customer']);

});
