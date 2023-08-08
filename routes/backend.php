<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:backend')->group(function () {
    Route::view('/', 'qrfeedz-backend::backend.home')
         ->name('backend.home');
});

//Route::get('/login', [\App\Http\Controllers\AuthController::class, 'loginView'])->name('loginView');
