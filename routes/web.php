<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// Route::middleware(['first'])->group(function () {
//     Route::get('/', function () {
//         // // 使用 first 和 second 中间件
//     });

//     Route::get('user/profile', function () {
//         // // 使用 first 和 second 中间件
//     });
// });
