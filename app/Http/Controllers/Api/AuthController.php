<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Consigner;
use App\Models\Driver;
use App\Models\Manager;

class AuthController extends BaseController
{
    public function __construct(Request $request)
    {
        $this->custom_guard = $request->user_type;
        $this->middleware('jwt.auth:'.$this->custom_guard, ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // 先解析userInfo,解析失败就返回错误
        $openid               = $request->input('openid', '');
        $user_type            = $request->input('user_type', '');
        $encrypted_data       = $request->input('encrypted_data', '');
        if (!empty($openid) && !empty($user_type) && !empty($encrypted_data)) {
            $wechat_app           = app('wechat.mini_program');
            $cache_key            = str_replace('{openid}', $openid, config('wechat.session_key'));
            $session_key          = Cache::get($cache_key);
            $decryptedData        = $wechat_app->encryptor->decryptData($session_key, $encrypted_data['iv'], $encrypted_data['encryptedData']);
            if (isset($decryptedData['openId']) && !empty($decryptedData['openId'])) {
                $where = ['openid' => $decryptedData['openId']];
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
                    if (!$token = Auth::guard($this->custom_guard)->login($exists->first())) {
                        return response()->json(['status' => false, 'message' => '无该用户信息，请联系管理员添加', 'code' => 40001 ]);
                    }
                    $exists->update(['nickname' => $decryptedData['nickName'], 'avatar' => $decryptedData['avatarUrl']]);
                } else {
                    return response()->json(['status' => false, 'message' => '无该用户信息，请联系管理员添加', 'code' => 40001]);
                }

                return $this->respondWithToken($token);
             } else {
                return response()->json(['status' => false, 'message' => '无该用户信息，请联系管理员添加'], 401);
             }
        } else {
            return response()->json(['status' => false, 'message' => '无该用户信息，请联系管理员添加'], 401);
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::guard($this->custom_guard)->logout();

        return response()->json(['message' => '成功退出']);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard($this->custom_guard)->factory()->getTTL() * 60
        ]);
    }
}
