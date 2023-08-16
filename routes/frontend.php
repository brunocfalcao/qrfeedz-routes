<?php

use Illuminate\Support\Facades\Route;
use QRFeedz\Frontend\Controllers\QRCodeController;

Route::get('/', function () {
    return 'welcome to the public website qrfeedz.';
});

Route::get(
    'qrcode/{uuid}', // This is the questionnaire uuid.
    [QRCodeController::class, 'go']
)->name('qrcode.go');

Route::get(
    'qrcode/pages/{uuid}', // This is the page instance uuid.
    [QRCodeController::class, 'renderPageInstance']
)->name('qrcode.render-page-instance');
