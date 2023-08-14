<?php

use Illuminate\Support\Facades\Route;
use QRFeedz\Services\Mail\Utils\TestTemplate;

Route::get('/tests/{view}', function ($view) {
    return view("qrfeedz-backend::tests/{$view}");
});

Route::get('/mailable/{template}', function (string $template) {
    return new TestTemplate($template);
});
