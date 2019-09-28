<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::namespace('Api')->group(function() {
    Route::get('test', 'TestController@index')->name('wechat.serve');
    Route::any('wechat', 'WechatController@serve')->name('wechat.serve');
    Route::get('wechat/user/session', 'WechatController@store')->name('wechat.store');
    Route::get('wechat/check', 'WechatController@check')->name('wechat.check');
    Route::post('wechat/binding', 'WechatController@binding')->name('wechat.binding');

    Route::post('authorizations', 'AuthController@store')->name('authorizations.store');
    Route::middleware(['auth:api'])->group(function () {
        Route::post('wechat/unbinding', 'WechatController@unbinding')->name('wechat.unbinding');
        Route::get('managers', 'ManagerController@index')->name('managers.index');
        Route::post('managers', 'ManagerController@store')->name('managers.store');
        Route::get('managers/{id}', 'ManagerController@show')->name('managers.show');
        Route::put('managers/{id}', 'ManagerController@patch')->name('managers.patch');

        Route::get('consigners', 'ConsignerController@index')->name('consigners.index');
        Route::get('consigners/{id}', 'ConsignerController@show')->name('consigners.show');
        Route::post('consigners', 'ConsignerController@store')->name('consigners.store');
        Route::put('consigners/{id}', 'ConsignerController@patch')->name('consigners.patch');

        Route::get('drivers', 'DriverController@index')->name('drivers.index');
        Route::get('drivers/{id}', 'DriverController@show')->name('drivers.show');
        Route::post('drivers', 'DriverController@store')->name('drivers.store');
        Route::put('drivers/{id}', 'DriverController@patch')->name('drivers.patch');
    });

    Route::get('logisticses', 'LogisticsController@index')->name('logisticses.index');
    Route::get('logisticses/{id}', 'LogisticsController@show')->name('logisticses.show');
    Route::post('logisticses', 'LogisticsController@store')->name('logisticses.store');
    Route::put('logisticses/{id}', 'LogisticsController@patch')->name('logisticses.patch');
    Route::put('logisticses/{id}/status', 'LogisticsController@status')->name('logisticses.status');
    Route::put('logisticses/{id}/drivers', 'LogisticsController@drivers')->name('logisticses.drivers');
    Route::put('logisticses/{id}/gps', 'LogisticsController@gps')->name('logisticses.gps');
});
