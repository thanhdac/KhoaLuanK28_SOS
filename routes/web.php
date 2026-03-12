<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiDocumentationController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/docs', [ApiDocumentationController::class, 'index']);
