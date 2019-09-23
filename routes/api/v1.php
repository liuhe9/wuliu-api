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
$api = app('Dingo\Api\Routing\Router');

// v1 version API
// add in header    Accept:application/vnd.lumen.v1+json
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api\V1',
    'middleware' => [
        // 'cors',
        // 'serializer',
         //'serializer:array', // if you want to remove data wrap
        'api.throttle',
    ],
    // each route have a limit of 20 of 1 minutes
    // 需要predis支持
    // 'limit' => 200, 'expires' => 1,
], function ($api) {
    $api->any('wechat', [
        'as' => 'wechat.serve',
        'uses' => 'WechatController@serve',
    ]);

    // Auth
    // login
    $api->post('authorizations', [
        'as' => 'authorizations.store',
        'uses' => 'AuthController@store',
    ]);

    // 所有管理员
    $api->get('managers', [
        'as' => 'managers.index',
        'uses' => 'ManagerController@index',
    ]);

    // 某个管理员
    $api->get('managers/{id}', [
        'as' => 'managers.show',
        'uses' => 'ManagerController@show',
    ]);

    // 添加管理员
    $api->post('managers', [
        'as' => 'managers.store',
        'uses' => 'ManagerController@store',
    ]);

     // 修改管理员姓名手机
     $api->put('managers/{id}', [
        'as' => 'managers.patch',
        'uses' => 'ManagerController@patch',
    ]);

    // 所有发货人
    $api->get('consigners', [
        'as' => 'consigners.index',
        'uses' => 'ConsignerController@index',
    ]);

    // 某个发货人
    $api->get('consigners/{id}', [
        'as' => 'consigners.show',
        'uses' => 'ConsignerController@show',
    ]);

    // 添加发货人
    $api->post('consigners', [
        'as' => 'consigners.store',
        'uses' => 'ConsignerController@store',
    ]);

     // 修改发货人姓名手机
     $api->put('consigners/{id}', [
        'as' => 'consigners.patch',
        'uses' => 'ConsignerController@patch',
    ]);

    // 所有司机
    $api->get('drivers', [
        'as' => 'drivers.index',
        'uses' => 'DriverController@index',
    ]);

    // 某个司机
    $api->get('drivers/{id}', [
        'as' => 'drivers.show',
        'uses' => 'DriverController@show',
    ]);

    // 添加司机
    $api->post('drivers', [
        'as' => 'drivers.store',
        'uses' => 'DriverController@store',
    ]);

     // 修改司机
     $api->put('drivers/{id}', [
        'as' => 'drivers.patch',
        'uses' => 'DriverController@patch',
    ]);


    // 所有发货单
    $api->get('logisticses', [
        'as' => 'logisticses.index',
        'uses' => 'LogisticsController@index',
    ]);

    $api->get('logisticses/{id}', [
        'as' => 'logisticses.show',
        'uses' => 'LogisticsController@show',
    ]);

    $api->post('logisticses', [
        'as' => 'logisticses.store',
        'uses' => 'LogisticsController@store',
    ]);

    $api->put('logisticses/{id}', [
        'as' => 'logisticses.patch',
        'uses' => 'LogisticsController@patch',
    ]);

    $api->put('logisticses/{id}/status', [
        'as' => 'logisticses.status',
        'uses' => 'LogisticsController@status',
    ]);

    $api->post('logisticses/{id}/drivers', [
        'as' => 'logisticses.drivers',
        'uses' => 'LogisticsController@drivers',
    ]);

    $api->post('logisticses/gps', [
        'as' => 'logisticses.gps',
        'uses' => 'LogisticsController@gps',
    ]);

    // User
    $api->post('users', [
        'as' => 'users.store',
        'uses' => 'UserController@store',
    ]);

    // user detail
    $api->get('users/{id}', [
        'as' => 'users.show',
        'uses' => 'UserController@show',
    ]);

    // POST
    // post list
    $api->get('posts', [
        'as' => 'posts.index',
        'uses' => 'PostController@index',
    ]);
    // post detail
    $api->get('posts/{id}', [
        'as' => 'posts.show',
        'uses' => 'PostController@show',
    ]);

    // POST COMMENT
    // post comment list
    $api->get('posts/{postId}/comments', [
        'as' => 'posts.comments.index',
        'uses' => 'CommentController@index',
    ]);

    /*
     * 对于authorizations 并没有保存在数据库，所以并没有id，那么对于
     * 刷新（put) 和 删除（delete) 我没有想到更好的命名方式
     * 所以暂时就是 authorizations/current 表示当前header中的这个token。
     * 如果 tokekn 保存保存在数据库，那么就是 authorizations/{id}，像 github 那样。
     */
    $api->put('authorizations/current', [
        'as' => 'authorizations.update',
        'uses' => 'AuthController@update',
    ]);

    // need authentication
    $api->group(['middleware' => 'api.auth'], function ($api) {
        /*
         * 对于authorizations 并没有保存在数据库，所以并没有id，那么对于
         * 刷新（put) 和 删除（delete) 我没有想到更好的命名方式
         * 所以暂时就是 authorizations/current 表示当前header中的这个token。
         * 如果 tokekn 保存保存在数据库，那么就是 authorizations/{id}，像 github 那样。
         */
        $api->delete('authorizations/current', [
            'as' => 'authorizations.destroy',
            'uses' => 'AuthController@destroy',
        ]);

        // USER
        // my detail
        $api->get('user', [
            'as' => 'user.show',
            'uses' => 'UserController@userShow',
        ]);

        // update part of me
        $api->patch('user', [
            'as' => 'user.update',
            'uses' => 'UserController@patch',
        ]);
        // update my password
        $api->put('user/password', [
            'as' => 'user.password.update',
            'uses' => 'UserController@editPassword',
        ]);

        // POST
        // user's posts index
        $api->get('user/posts', [
            'as' => 'user.posts.index',
            'uses' => 'PostController@userIndex',
        ]);
        // create a post
        $api->post('posts', [
            'as' => 'posts.store',
            'uses' => 'PostController@store',
        ]);
        // update a post
        $api->put('posts/{id}', [
            'as' => 'posts.update',
            'uses' => 'PostController@update',
        ]);
        // update part of a post
        $api->patch('posts/{id}', [
            'as' => 'posts.patch',
            'uses' => 'PostController@patch',
        ]);
        // delete a post
        $api->delete('posts/{id}', [
            'as' => 'posts.destroy',
            'uses' => 'PostController@destroy',
        ]);

        // POST COMMENT
        // create a comment
        $api->post('posts/{postId}/comments', [
            'as' => 'posts.comments.store',
            'uses' => 'CommentController@store',
        ]);
        $api->put('posts/{postId}/comments/{id}', [
            'as' => 'posts.comments.update',
            'uses' => 'CommentController@update',
        ]);
        // delete a comment
        $api->delete('posts/{postId}/comments/{id}', [
            'as' => 'posts.comments.destroy',
            'uses' => 'CommentController@destroy',
        ]);
    });
});
