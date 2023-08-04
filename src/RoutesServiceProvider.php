<?php

namespace QRFeedz\Routes;

use Brunocfalcao\LaravelHelpers\Utils\DomainPatternIdentifier;
use Illuminate\Http\Exceptions\HttpResponseException;
use QRFeedz\Foundation\Abstracts\QRFeedzServiceProvider;

class RoutesServiceProvider extends QRFeedzServiceProvider
{
    public function boot()
    {
        $this->identifyAndLoadRoutes();
    }

    public function register()
    {
        //
    }

    protected function identifyAndLoadRoutes()
    {
        /**
         * Load additional specific routes, given the url context:
         *
         *  admin.qrfeedz.(ch/com/ai) => backend
         *  (www.)qrfeedz.(ch/com/ai) => frontend
         *
         *  if it's a local environment, then we will have:
         *
         *  admin.qrfeedz.local => backend
         *  (www.)qrfeedz.local => frontend
         *
         *  Anything else should return an HTTP permission denied (HTTP 400).
         */
        if (! app()->runningInConsole()) {

            /**
             * The logic is a bit complex :)
             *
             * 1st global validation:
             * HTTP request validation. Should always be HTTPS except if
             * the top_level_domain key is "local" (because it will be a local
             * environment).
             *
             * If environment != "local" then we need to be more strict. This
             * means we don't accept top_level_domain = local since it's not a local
             * development.
             *
             * Frontend validation. Should not have a subdomain, and have
             * qrfeedz as the domain name. Suffix can be .com, .ai, .ch and
             * .local (for local development).
             *
             * Backend validation. Should have "admin" as subdomain and have
             * qrfeedz as the domain name. Suffix can be .com, .ai, .ch and
             * .local (for local development).
             *
             * Remote development environment: The same applies but for the
             * domain "(admin.)qrfeedz-dev.com". Everything works the same,
             * but this is a non-productive environment to test new things,
             * with test data.
             *
             * Staging environment:  The same applies but for the
             * domain "(admin.)qrfeedz-staging.com". Everything works the same,
             * but this is a staging environment to test bugs or new things
             * but it will always use PRODUCTION replicated data.
             */
            $parts = DomainPatternIdentifier::parseUrl(request()->fullUrl());

            $pass = false;

            /**
             * Validate the major parts values:
             * "top_level_domain" as null, ai, ch, com and local.
             * "domain" as qrfeedz, qrfeedz-staging, qrfeedz-dev and localhost.
             * "subdomain" as null or admin.
             * "port" as 80 or 8000.
             *
             * No need to create an utility method since this logic is just
             * tested here.
             */
            $pass = in_array(
                $parts['top_level_domain'],
                [null, 'ai', 'ch', 'com', 'local']
            )
                &&
                in_array(
                    $parts['domain'],
                    ['qrfeedz', 'localhost', 'qrfeedz-dev', 'qrfeedz-staging']
                )
                &&
                in_array(
                    $parts['subdomain'],
                    [null, 'admin']
                ) &&
                in_array(
                    $parts['port'],
                    ['80', '8000']
                ) &&
                ($parts['http_scheme'] == 'https' ?
                    $parts['domain'] != 'localhost' :
                    $parts['domain'] == 'localhost');

            if (! $pass) {
                throw new HttpResponseException(
                    response('The requested URL is invalid or unauthorized.', 400)
                );
            }

            /**
             * Next step is to understand what routes should we load. On this
             * case, we should load the backend routes if the subdomain is
             * "admin". If not, we load the frontend routes.
             * The common routes are always loaded.
             * If we are in a non-production environment, we also load the
             * testing routes.
             *
             * This is just LOADING routes files. The middleware logic should
             * be inside the routes file itself.
             */
            $this->loadRoutesFrom(__DIR__.'/../routes/common.php');

            if ($parts['subdomain'] == 'admin') {
                $this->loadRoutesFrom(__DIR__.'/../routes/backend.php');
            }

            if ($parts['subdomain'] == null) {
                $this->loadRoutesFrom(__DIR__.'/../routes/frontend.php');
            }

            if (app()->environment() != 'production') {
                $this->loadRoutesFrom(__DIR__.'/../routes/testing.php');
            }
        } else {
            // We just load the common, console and testing routes.
            $this->loadRoutesFrom(__DIR__.'/../routes/common.php');
            $this->loadRoutesFrom(__DIR__.'/../routes/console.php');
            $this->loadRoutesFrom(__DIR__.'/../routes/testing.php');
        }
    }
}
