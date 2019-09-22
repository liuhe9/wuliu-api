<?php

namespace App\Http\Validations;

use App\Http\Validations\Rules\MobileValidationRule;
use Illuminate\Validation\Rule;

class LogisticsValidation
{
    public function store()
    {
        return [
            'rules' => [
                'tracking_no'  => 'required|string|unique:logisticses',
                'consigner_id' => 'required',
                'receiver_name' => 'required',
                'receiver_mobile' => ['required', 'string', new MobileValidationRule],
                'from_address' => 'required',
                'from_gps' => 'required',
                'to_address' => 'required',
                'to_gps' => 'required',
            ],
            'messages' => [
                'tracking_no.*' => '发货单号必填',
                'consigner_id.required'  => '发货人错误',
                'receiver_name.required'  => '收货人必填',
                'receiver_mobile.required'  => '手机格式错误，请填写11位手机号',
                'from_address.required'  => '发货地必填',
                'from_gps.required'  => '发货地点必选',
                'to_address.required'  => '收货地必填',
                'to_gps.required'  => '收货地点必选',
            ],
        ];
    }
}
