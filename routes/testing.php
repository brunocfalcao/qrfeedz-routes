<?php

use Illuminate\Support\Facades\Route;

Route::get('/tests/{view}', function ($view) {
    return view("qrfeedz-backend::tests/{$view}");
});
