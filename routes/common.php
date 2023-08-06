<?php

use Illuminate\Support\Facades\Route;

Route::redirect('contact-us', 'mailto::contact@qrfeedz.ch', 301)
    ->name('contact-us');
