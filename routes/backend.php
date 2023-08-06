<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'welcome to the backend.';
});

//Route::get('/password/reset/{token}', [PagesController::class, 'layoutsSignUp1'])->name('layouts/sign-up-1');
