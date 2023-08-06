<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'welcome to the backend.';
});

Route::middleware('auth:backend')->group(function () {
    Route::get('home', function () {
        return 'welcome to the backend home';
    })->name('backend.home');
});

//Route::get('/login', [\App\Http\Controllers\AuthController::class, 'loginView'])->name('loginView');
