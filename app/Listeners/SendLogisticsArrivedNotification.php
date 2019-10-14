<?php

namespace App\Listeners;

use App\Events\LogisticsArrived;
use App\Models\Logistics\Status\ArrivedLogisticsStatus;
use App\Models\WxTemplate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendLogisticsArrivedNotification
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
     * @param  LogisticsArrived  $event
     * @return void
     */
    public function handle(LogisticsArrived $event)
    {
        $logistics     = $event->logistics;
        $template_key  = WxTemplate::getInitTemplateSettings('wlztbhtz')['template_id_short'];
        $template_info = WxTemplate::where(['template_id_short' => $template_key])->first();
        $wechat_app    = app('wechat.mini_program');
        $result        = $wechat_app->uniform_message->send([
            'touser'          => $logistics->consignerInfo->openid,
            'mp_template_msg' => [
                'appid'       => config('wechat.official_account.default.app_id'),
                'template_id' => $template_info->template_id,
                'miniprogram' => [
                    'appid'    => config('wechat.mini_program.default.app_id'),
                    // 'pagepath' => 'pages/consigner/index?tab_cur='.ArrivedLogisticsStatus::STATUS_CODE,
                ],
                'data'        => [
                    'first'    => ArrivedLogisticsStatus::STATUS_NAME,
                    'keyword1' => $logistics->tracking_no,
                    'keyword2' => $logistics->product_desc,
                    'keyword3' => $logistics->from_address.'-'.$logistics->to_address,
                    'keyword4' => $logistics->created_at,
                ],
            ],
        ]);
        Log::info($result);
    }
}
