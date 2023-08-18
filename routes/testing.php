<?php

use Illuminate\Support\Facades\Route;
use QRFeedz\Cube\Models\Questionnaire;
use QRFeedz\Services\Mail\Utils\TestTemplate;

Route::get('/tests/{view}', function ($view) {
    return view("qrfeedz-backend::tests/{$view}");
});

Route::get('/mailable/{template}', function (string $template) {
    return new TestTemplate($template);
});

Route::get(
    'qrcode/first', // This a redirect to the first valid qrcode.
    function () {

        return redirect(
            route(
                'qrcode.go',
                ['uuid' => Questionnaire::firstWhere('is_active', true)
                                        ->first()->uuid]
            )
        );
    }
)->name('qrcode.first');
