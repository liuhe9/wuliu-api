<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Elasticsearch\ClientBuilder;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // dingo findOrFail
        // app('api.exception')->register(function (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
        //     abort(404);
        // });
    }
}
