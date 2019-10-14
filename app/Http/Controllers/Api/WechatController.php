<?php

namespace App\Http\Controllers\Api;

use App\Models\Consigner;
use App\Models\Driver;
use App\Models\Manager;
use App\Models\WxTemplate;
use Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use function GuzzleHttp\json_decode;

class WechatController extends BaseController
{
    /**
     * 事件接收
     *
     * @return void
     */
    public function serve(Request $request)
    {
        Log::info('request arrived.');
        $wx_type = $request->input('wx_type', 'mini_program');
        $wechat_app = app('wechat.'.$wx_type);
        $wechat_app->server->push(function($message){
            Log::info($message);
            return "欢迎关注 overtrue！";
        });

        return $wechat_app->server->serve();
    }

    /**
     * 小程序session_key,openid
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $wechat_app = app('wechat.mini_program');
        $result     = $wechat_app->auth->session($request->get('code'));
        $cache_key  = str_replace('{openid}', $result['openid'], config('wechat.session_key'));
        if (isset($result['session_key']) && isset($result['openid'])) {
            Cache::put($cache_key, $result['session_key']);
        }

        return response()->json(['openid' => $result['openid']], 200);
    }

    /**
     * 检查是否绑定了
     *
     * @param Request $request
     * @return void
     */
    public function check(Request $request)
    {
        $auth_type  = $request->input('auth_type');
        $openid     = $request->input('openid');
        $where      = ['openid' => $openid];
        switch($auth_type) {
            case 'manager':
                $exists = Manager::where($where)->exists();
                break;
            case 'driver':
                $exists = Driver::where($where)->exists();
                break;
            case 'consigner':
                $exists = Consigner::where($where)->exists();
                break;
        }
        return response()->json(['status' => $exists]);
    }

    /**
     * 绑定
     *
     * @param Request $request
     * @return void
     */
    public function binding(Request $request)
    {
        $user_type            = $request->input('user_type');
        $openid               = $request->input('openid');
        $encrypted_data       = $request->input('encrypted_data');
        $wechat_app           = app('wechat.mini_program');
        $cache_key            = str_replace('{openid}', $openid, config('wechat.session_key'));
        $session_key          = Cache::get($cache_key);
        $decryptedData        = $wechat_app->encryptor->decryptData($session_key, $encrypted_data['iv'], $encrypted_data['encryptedData']);
        $binding_result       = ['status' => false];
        if (isset($decryptedData['purePhoneNumber'])) {
            $where = ['mobile' => $decryptedData['purePhoneNumber']];
            switch($user_type) {
                case 'manager':
                    $exists = Manager::where($where);
                    break;
                case 'driver':
                    $exists = Driver::where($where);
                    break;
                case 'consigner':
                    $exists = Consigner::where($where);
                    break;
            }

            if (!empty($exists->first())) {
                $result = $exists->update(['openid' => $openid]);
                Manager::where(['openid' => $openid])
                    ->where('mobile', '<>', $decryptedData['purePhoneNumber'])
                    ->update(['openid' => '', 'avatar' => '', 'nickname' => '']);
                if (!empty($result)) {
                    $binding_result['status'] = true;
                } else {
                    $binding_result['errMsg'] = '更新数据失败';
                }
            } else {
                $binding_result['errMsg'] = '未查询到该手机号绑定的用户，请与管理员联系';
            }
        } else {
            $binding_result['errMsg'] = '解密手机号错误';
        }

        return response()->json($binding_result, 200);
    }

    /**
     * 解绑
     *
     * @param Request $request
     * @return void
     */
    public function unbinding(Request $request)
    {
        $id         = $request->input('id');
        $user_type  = $request->input('user_type');
        $where      = ['id' => $id];
        switch($user_type) {
            case 'manager':
                $model = Manager::where($where);
                break;
            case 'driver':
                $model = Driver::where($where);
                break;
            case 'consigner':
                $model = Consigner::where($where);
                break;
        }

        $result = $model->update(['openid' => '', 'avatar' => '', 'nickname' => '']);
        return response()->json(['status' => $result]);
    }
}
