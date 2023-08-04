<?php

namespace QRFeedz\Routes;

use Brunocfalcao\Tracer\Middleware\VisitTracing;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use QRFeedz\Foundation\Abstracts\QRFeedzServiceProvider;

class RoutesServiceProvider extends QRFeedzServiceProvider
{
    public function boot()
    {
        $this->loadRoutes();
    }

    public function register()
    {
        //
    }

    protected function loadRoutes()
    {
        // Load default backend routes.
        $routesPath = __DIR__.'/../routes/default.php';

        Route::middleware([
            'web',
            VisitTracing::class,
        ])
            ->group(function () use ($routesPath) {
                include $routesPath;
            });
    }
}
