<?php

namespace App\Http\Validations;

use App\Http\Validations\Rules\MobileValidationRule;
use App\Http\Validations\Rules\IdcardValidationRule;
use Illuminate\Validation\Rule;

class DriverValidation
{
    public function store()
    {
        return [
            'rules' => [
                'mobile'   => ['required', 'string', new MobileValidationRule, 'unique:drivers'],
                'name'     => 'required|string',
                'id_card'  => ['required', 'string', new IdcardValidationRule]
            ],
            'messages' => [
                'mobile.unique'     => '手机号已存在，请修改',
                'mobile.*'          => '手机格式错误，请填写11位手机号',
                'name.required'     => '姓名必填',
                'id_card.required'  => '身份证必填',
            ],
        ];
    }

    public function patch($request)
    {
        return [
            'rules' => [
                'mobile'   => ['required', 'string', new MobileValidationRule, Rule::unique('drivers')->ignore($request['id'])],
                'name'     => 'required|string',
                'id_card'  => ['required', 'string', new IdcardValidationRule]
            ],
            'messages' => [
                'mobile.unique'     => '手机号已存在，请修改',
                'mobile.*'          => '手机格式错误，请填写11位手机号',
                'name.required'     => '姓名必填',
                'id_card.required'  => '身份证必填',
            ],
        ];
    }
}
