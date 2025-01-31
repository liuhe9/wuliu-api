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

Route::namespace('Api')->group(function() {
    Route::post('login', 'AuthController@login')->name('login');
    Route::post('logout', 'AuthController@logout')->name('auth.logout');

    Route::get('companies', 'CompanyController@index')->name('companies.index');

    Route::any('wechat', 'WechatController@serve')->name('wechat.serve');
    Route::get('wechat/user/session', 'WechatController@store')->name('wechat.store');
    Route::get('wechat/check', 'WechatController@check')->name('wechat.check');
    Route::post('wechat/binding', 'WechatController@binding')->name('wechat.binding');

    Route::post('files', 'FileController@store')->name('file.store');
    Route::get('logisticss/status', 'LogisticsController@myStatus')->name('logisticss.status');

    // 管理员
    Route::group(['middleware' => ['auth:manager']] , function(){
        Route::post('wechat/unbinding', 'WechatController@unbinding')->name('wechat.unbinding');
        Route::get('managers', 'ManagerController@index')->name('managers.index');
        Route::get('managers/me', 'ManagerController@me')->name('managers.me');
        Route::post('managers', 'ManagerController@store')->name('managers.store');
        Route::get('managers/{id}', 'ManagerController@show')->name('managers.show');
        Route::put('managers/{id}', 'ManagerController@patch')->name('managers.patch');

        Route::put('manager/logistics/{id}/status', 'LogisticsController@status')->name('manager.logistics.status');
        Route::put('manager/logistics/{id}/images', 'LogisticsController@setImages')->name('manager.logistics.images');

        Route::get('consigners', 'ConsignerController@index')->name('consigners.index');
        Route::get('consigners/{id}', 'ConsignerController@show')->name('consigners.show');
        Route::post('consigners', 'ConsignerController@store')->name('consigners.store');
        Route::put('consigners/{id}', 'ConsignerController@patch')->name('consigners.patch');

        Route::get('drivers', 'DriverController@index')->name('drivers.index');
        Route::get('drivers/{id}', 'DriverController@show')->name('drivers.show');
        Route::post('drivers', 'DriverController@store')->name('drivers.store');
        Route::put('drivers/{id}', 'DriverController@patch')->name('drivers.patch');

        Route::get('logisticss', 'LogisticsController@index')->name('logisticss.index');
        Route::get('logisticss/{id}', 'LogisticsController@show')->name('logisticss.show');
        Route::put('logisticss/{id}', 'LogisticsController@patch')->name('logisticss.patch');
        Route::put('logisticss/{id}/drivers', 'LogisticsController@setDrivers')->name('logisticss.drivers');

        Route::get('logistics/statisticss', 'LogisticsController@statistics')->name('logisticss.statistics');

        Route::post('companies', 'CompanyController@store')->name('companies.store');
        Route::put('companies/{id}', 'CompanyController@patch')->name('companies.patch');
    });

    // 客户
    Route::group(['middleware' => ['auth:consigner']], function(){
        Route::post('logisticss', 'LogisticsController@store')->name('logisticss.store');
        Route::get('consigner/logisticss', 'LogisticsController@index')->name('consigner.logisticss.index');
        Route::get('consigner/logisticss/{id}', 'LogisticsController@show')->name('consigner.logisticss.show');
        Route::post('consigner/logisticss', 'LogisticsController@store')->name('consigner.logisticss.store');
        Route::put('consigner/logisticss/{id}', 'LogisticsController@patch')->name('consigner.logisticss.patch');
        Route::put('consigner/logisticss/{id}/status', 'LogisticsController@status')->name('consigner.logisticss.status');
    });

    // 司机
    Route::group(['middleware' => ['auth:driver']], function(){
        Route::get('driver/logisticss', 'LogisticsController@index')->name('driver.logisticss.index');
        Route::get('driver/logisticss/{id}', 'LogisticsController@show')->name('driver.logisticss.show');
        Route::post('driver/logisticss', 'LogisticsController@store')->name('driver.logisticss.store');
        Route::put('driver/logisticss/{id}', 'LogisticsController@patch')->name('driver.logisticss.patch');

        Route::put('driver/logistics/{id}/status', 'LogisticsController@status')->name('driver.logistics.status');
        Route::put('driver/logistics/{id}/images', 'LogisticsController@setImages')->name('driver.logistics.images');

        Route::put('gps', 'LogisticsController@gps')->name('logisticss.gps');
    });

});
