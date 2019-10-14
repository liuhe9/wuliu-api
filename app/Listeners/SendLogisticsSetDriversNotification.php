<?php

namespace App\Listeners;

use App\Events\LogisticsSetDrivers;
use App\Models\WxTemplate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendLogisticsSetDriversNotification
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
     * @param  LogisticsSetDrivers  $event
     * @return void
     */
    public function handle(LogisticsSetDrivers $event)
    {
        $logistics     = $event->logistics;
        $drivers       = $logistics->drivers;
        $template_key  = WxTemplate::getInitTemplateSettings('ddfptz')['template_id_short'];
        $template_info = WxTemplate::where(['template_id_short' => $template_key])->first();
        $wechat_app    = app('wechat.mini_program');

        foreach($drivers as $value) {
            $result = $wechat_app->uniform_message->send([
                'touser'          => $value->driver->openid,
                'mp_template_msg' => [
                    'appid'       => config('wechat.official_account.default.app_id'),
                    'template_id' => $template_info->template_id,
                    'miniprogram' => [
                        'appid'    => config('wechat.mini_program.default.app_id'),
                        // 'pagepath' => 'pages/driver/index',
                    ],
                    'data'        => [
                        'keyword1' => $logistics->tracking_no,
                        'keyword2' => $logistics->from_address,
                        'keyword3' => $logistics->to_address,
                    ],
                ],
            ]);
            Log::info($result);
        }
    }
}
