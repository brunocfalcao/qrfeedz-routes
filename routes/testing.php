<?php

use Illuminate\Support\Facades\Route;
use QRFeedz\Cube\Models\User;
use QRFeedz\Services\Mail\Users\ResetUserPasswordMail;

Route::get('/tests/{view}', function ($view) {
    return view("qrfeedz-backend::tests/{$view}");
});

Route::get('/mailable', function () {
    $user = User::find(1);
    $resetLink = $user->getPasswordResetLink(true);

    return new ResetUserPasswordMail($user, [
        'invalidate' => true,
        'resetLink' => $resetLink,
    ]);
});
