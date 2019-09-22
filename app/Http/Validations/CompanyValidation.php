<?php

namespace App\Http\Validations;

class CompanyValidation
{
    public function store()
    {
        return [
            'rules' => [
                'mobile'   => 'required|string|size:11|unique:companies',
                'name'     => 'required|string',
                'password' => 'required|string|min:6|max:20',
            ],
            'messages' => [
                'mobile.*'          => '手机格式错误，请填写11位手机号',
                'name.required'     => '姓名必填',
                'password.*'        => '密码必填,长度6~20',
            ],
        ];
    }
}
