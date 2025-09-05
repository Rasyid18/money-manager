<?php

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Middleware\RefreshTokenExpiration;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::middleware(['auth:sanctum', RefreshTokenExpiration::class])->group(function () {
    Route::apiResource('accounts', AccountController::class);
    Route::prefix('accounts')->group(function () {
        Route::patch('{account}/restore', [AccountController::class, 'restore'])->name('accounts.restore');
        Route::delete('{account}/remove', [AccountController::class, 'remove'])->name('accounts.remove');
    });
});
