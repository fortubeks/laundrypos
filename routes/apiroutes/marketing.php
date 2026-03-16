<?php

use App\Http\Controllers\MarketingController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'marketing', 'middleware' => ['auth:sanctum']], function () {
    Route::post('/send', [MarketingController::class, 'send']);
});
