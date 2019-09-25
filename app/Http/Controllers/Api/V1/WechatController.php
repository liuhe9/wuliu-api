<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Log;

class WechatController extends BaseController
{
    public function serve()
    {
        Log::info('request arrived.');
        $app = app('wechat.mini_program');
        $app->server->push(function($message){
            Log::info($message);
            return "欢迎关注 overtrue！";
        });

        return $app->server->serve();
    }
}
