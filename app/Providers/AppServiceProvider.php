<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->addAcceptableJsonType();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Add "application/json" to the "Accept" header for the current request.
     */
    protected function addAcceptableJsonType()
    {
        $this->app->rebinding('request', function ($app, $request) {
            if ($request->is('api/*')) {
                $accept = $request->header('Accept');

                if (! str_contains($accept, ['/json', '+json'])) {
                    $accept = rtrim('application/json,'.$accept, ',');
                    $request->headers->set('Accept', $accept);
                    $request->server->set('HTTP_ACCEPT', $accept);
                    $_SERVER['HTTP_ACCEPT'] = $accept;
                }
            }
        });
    }
}
