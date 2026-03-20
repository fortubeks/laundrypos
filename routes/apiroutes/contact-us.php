<?php

use App\Http\Controllers\ContactSubmissionController;
use Illuminate\Support\Facades\Route;

Route::post('/contact-us', [ContactSubmissionController::class, 'store']);
