<?php

namespace App\Listeners;

use App\Events\LogisticsStart;
use App\Models\Logistics\Status\StartLogisticsStatus;
use App\Models\Manager;
use App\Models\WxTemplate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendLogisticsStartNotification
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
     * @param  LogisticsStart  $event
     * @return void
     */
    public function handle(LogisticsStart $event)
    {
        $logistics     = $event->logistics;
        $managers      = Manager::where('openid', '<>', '')->get();
        $template_key  = WxTemplate::getInitTemplateSettings('ddsctz')['template_id_short'];
        $template_info = WxTemplate::where(['template_id_short' => $template_key])->first();
        $wechat_app    = app('wechat.mini_program');
        foreach($managers as $value) {
            $result = $wechat_app->uniform_message->send([
                'touser'          => $value->openid,
                'mp_template_msg' => [
                    'appid'       => config('wechat.official_account.default.app_id'),
                    'template_id' => $template_info->template_id,
                    'miniprogram' => [
                        'appid'    => config('wechat.mini_program.default.app_id'),
                        // 'pagepath' => 'pages/manager/logistics/index?tab_cur='.StartLogisticsStatus::STATUS_CODE,
                    ],
                    'data'        => [
                        'first'    => '单号：'.$logistics->tracking_no,
                        'keyword1' => $logistics->consignerInfo->name,
                        'keyword2' => $logistics->consignerInfo->mobile,
                        'keyword3' => $logistics->from_address,
                        'keyword4' => $logistics->to_address,
                        'remark'   => $logistics->product_desc,
                    ],
                ],
            ]);
            Log::info($result);
        }
    }
}
