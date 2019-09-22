<?php

namespace App\Http\Validations;

use App\Http\Validations\Rules\MobileValidationRule;
use Illuminate\Validation\Rule;

class ConsignerValidation
{
    public function store()
    {
        return [
            'rules' => [
                'mobile'   => ['required', 'string', new MobileValidationRule, 'unique:consigners'],
                'name'     => 'required|string',
            ],
            'messages' => [
                'mobile.unique'     => '手机号已存在，请修改',
                'mobile.*'          => '手机格式错误，请填写11位手机号',
                'name.required'     => '姓名必填',
            ],
        ];
    }

    public function patch($request)
    {
        return [
            'rules' => [
                'mobile'   => ['required', 'string', new MobileValidationRule, Rule::unique('consigners')->ignore($request['id'])],
                'name'     => 'required|string',
            ],
            'messages' => [
                'mobile.unique'     => '手机号已存在，请修改',
                'mobile.*'          => '手机格式错误，请填写11位手机号',
                'name.required'     => '姓名必填',
            ],
        ];
    }
}
