<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Middleware\RefreshTokenExpiration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::get('/user', function (Request $request) {
    Log::debug('enter');
    return $request->user();
})->middleware(['auth:sanctum', RefreshTokenExpiration::class]);
