<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\TarotDiaryController;
use App\Http\Controllers\TarotDrawController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// 註冊、驗證使用者信箱、登入、登出、刷新 token
Route::prefix('auth')->group(function (): void {
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:6,1');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1');
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify');

    Route::middleware('auth:api')->group(function (): void {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);

        // 使用第三方登入進行初次註冊的使用者，需補填性別、生日，並可以修改名稱
        Route::post('/complete-profile', [ProfileController::class, 'completeProfile']);
        Route::post('/diaries', [TarotDiaryController::class, 'store']);
        Route::get('/diaries/{id}', [TarotDiaryController::class, 'show']);
        Route::put('/diaries/{id}', [TarotDiaryController::class, 'update']);
    });
});

Route::get('/tarot/draw', [TarotDrawController::class, 'drawCard']);

// Google 登入
Route::prefix('auth/{provider}')->group(function (): void {
    Route::get('redirect', [SocialAuthController::class, 'redirect']);
    Route::get('callback', [SocialAuthController::class, 'callback']);
    Route::post('login', [SocialAuthController::class, 'login']);
});

// 取得使用者個人資料、編輯使用者個人資料
Route::middleware('auth:api')->prefix('user')->group(function (): void {
    Route::get('/me', [UserController::class, 'me']);
    Route::put('/update', [UserController::class, 'update']);
});
