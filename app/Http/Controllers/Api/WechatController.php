<?php

namespace App\Http\Controllers\Api;

use Log;
use Illuminate\Http\Request;

use function GuzzleHttp\json_decode;

class WechatController extends BaseController
{
    public function serve()
    {
        Log::info('request arrived.');
        $wechat_app = app('wechat.mini_program');
        $wechat_app->server->push(function($message){
            Log::info($message);
            return "欢迎关注 overtrue！";
        });

        return $wechat_app->server->serve();
    }

    public function store(Request $request)
    {
        $wechat_app = app('wechat.mini_program');
        $result = $wechat_app->auth->session($request->get('code'));
        return response()->json(['openid' => $result['openid']], 200);
    }
}
