<?php

namespace App\Listeners;

use App\Events\LogisticsConfirm;
use App\Models\Logistics\Status\ConfirmLogisticsStatus;
use App\Models\WxTemplate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendLogisticsConfirmNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  LogisticsConfirm  $event
     * @return void
     */
    public function handle(LogisticsConfirm $event)
    {
        $logistics     = $event->logistics;
        $template_key  = WxTemplate::getInitTemplateSettings('ddysltz')['template_id_short'];
        $template_info = WxTemplate::where(['template_id_short' => $template_key])->first();
        $wechat_app    = app('wechat.mini_program');
        $result        = $wechat_app->uniform_message->send([
            'touser'          => $logistics->consignerInfo->openid,
            'mp_template_msg' => [
                'appid'       => config('wechat.official_account.default.app_id'),
                'template_id' => $template_info->template_id,
                'miniprogram' => [
                    'appid'    => config('wechat.mini_program.default.app_id'),
                    // 'pagepath' => 'pages/consigner/index?tab_cur='.ConfirmLogisticsStatus::STATUS_CODE,
                ],
                'data'        => [
                    'keyword1' => $logistics->tracking_no,
                    'keyword2' => '已确定，将尽快为您安排司机发货',
                ],
            ],
        ]);
        Log::info($result);
    }
}
