<?php

namespace App\Http\Controllers\Api;

use Log;

class WechatController extends BaseController
{
    public function serve()
    {
        Log::info('request arrived.');
        $wechat_app = app('wechat.mini_program');
        echo '<pre>';print_r($wechat_app);exit;
        $wechat_app->server->push(function($message){
            Log::info($message);
            return "欢迎关注 overtrue！";
        });

        return $wechat_app->server->serve();
    }
}
