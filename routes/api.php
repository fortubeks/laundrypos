<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

/**
 *
 * Load the auth routes to be processed first
 */
require __DIR__ . '/apiroutes/auth.php';

/**
 * include all route files in the apiroutes folder except auth.php
 *
 */
$api_folder = __DIR__ . '/apiroutes';
$dir        = new DirectoryIterator($api_folder);
foreach ($dir as $file) {
    if ($file->isFile() && str_contains($file->getFilename(), '.php') && $file->getFilename() !== 'auth.php') {
        require __DIR__ . '/apiroutes/' . $file->getFilename();
    }
}

Route::get('/documentations', function () {

    return response()->json([
        'message'   => 'Welcome to Laundry POS API',
        'version'   => '1.0.0',
        'endpoints' => [
            'auth'          => '/api/auth',
            'customers'     => '/api/customers',
            'orders'        => '/api/orders',
            'service-items' => '/api/service-items',
            'laundry-items' => '/api/laundry-items',
        ],
    ]);
});
