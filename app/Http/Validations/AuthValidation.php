<?php

namespace App\Http\Validations;

class AuthValidation
{
    public function login()
    {
        return [
            'rules' => [
                'openid'   => 'required|string',
            ],
            'messages' => [
                'openid.*' => '用户信息获取失败',
            ],
        ];
    }
}
