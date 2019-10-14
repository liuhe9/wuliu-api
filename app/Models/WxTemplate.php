<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WxTemplate extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'template_id_short', 'template_name', 'template_example', 'template_id'
    ];

    public static function getInitTemplateSettings($key = null)
    {
        $tpls = [
            'ddsctz' => [
                'template_id_short' => 'OPENTM416318057',
                'template_name' => '订单生成通知',
                'template_example' => '{{first.DATA}} 发货人姓名：{{keyword1.DATA}} 发货人电话：{{keyword2.DATA}} 发货地址：{{keyword3.DATA}} 目的地址：{{keyword4.DATA}} {{remark.DATA}}',
            ],

            'ddysltz' => [
                'template_id_short' => 'OPENTM205062253',
                'template_name' => '订单已受理通知',
                'template_example' => '{{first.DATA}} 订单号：{{keyword1.DATA}} 内容：{{keyword2.DATA}} {{remark.DATA}}',
            ],

            'wlztbhtz' => [
                'template_id_short' => 'OPENTM401774396',
                'template_name' => '物流状态变化通知',
                'template_example' => '{{first.DATA}} 订单编号：{{keyword1.DATA}} 货物信息：{{keyword2.DATA}} 运输线路：{{keyword3.DATA}} 下单日期：{{keyword4.DATA}} {{remark.DATA}}',
            ],

            'ddwctz' => [
                'template_id_short' => 'OPENTM410946561',
                'template_name' => '订单完成通知',
                'template_example' => '{{first.DATA}}  订单号：{{keyword1.DATA}}  完成时间：{{keyword2.DATA}} {{remark.DATA}}',
            ],
            'ddfptz' => [
                'template_id_short' => 'OPENTM414980504',
                'template_name' => '订单分配通知',
                'template_example' => '{{first.DATA}} 物流单号：{{keyword1.DATA}} 始发地：{{keyword2.DATA}} 目的地：{{keyword3.DATA}} {{remark.DATA}}',
            ],
        ];
        return $key == null ? $tpls : $tpls[$key];
    }
}
