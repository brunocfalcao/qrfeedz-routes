<?php

use Illuminate\Support\Facades\Route;
use QRFeedz\Frontend\Controllers\QRCodeController;

Route::get('/', function () {
    return 'welcome to the public website qrfeedz.';
});

Route::get(
    'qrcode/go/{uuid}', // This is qr code validation to a questionnaire.
    [QRCodeController::class, 'go']
)->name('qrcode.go');

Route::get(
    'qrcode/render', // This is the questionnaire rendering framework.
    [QRCodeController::class, 'render']
)->name('qrcode.render');
