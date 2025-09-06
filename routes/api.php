<?php

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BudgetController;
use App\Http\Middleware\RefreshTokenExpiration;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::middleware(['auth:sanctum', RefreshTokenExpiration::class])->group(function () {
    Route::apiResource('accounts', AccountController::class);
    Route::prefix('accounts')->group(function () {
        Route::patch('{account}/restore', [AccountController::class, 'restore'])->name('accounts.restore');
        Route::delete('{account}/remove', [AccountController::class, 'remove'])->name('accounts.remove');
    });

    Route::apiResource('budgets', BudgetController::class);
    Route::prefix('budgets')->group(function () {
        Route::patch('{budget}/restore', [BudgetController::class, 'restore'])->name('budgets.restore');
        Route::delete('{budget}/remove', [BudgetController::class, 'remove'])->name('budgets.remove');
    });
});
