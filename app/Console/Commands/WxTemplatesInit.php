<?php

namespace App\Console\Commands;

use App\Models\WxTemplate;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class WxTemplatesInit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wx:template-init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'wechat templates init';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $all_templates        = WxTemplate::getInitTemplateSettings();
        $wechat_app           = app('wechat.official_account');
        $wx_exists_templates  = $wechat_app->template_message->getPrivateTemplates();
        $title_exists         = empty($wx_exists_templates) ? [] : $wx_exists_templates['template_list'];
        $title_arr            = Arr::pluck($title_exists, 'title');
        foreach($all_templates as $key => $value) {
            if (in_array($value['template_name'], $title_arr)) {
                foreach($title_exists as $k => $v) {
                    if ($value['template_name'] == $v['title']) {
                        $value['template_id'] = $v['template_id'];
                        WxTemplate::updateOrCreate(['template_id_short' => $value['template_id_short']], $value);
                        break;
                    }
                }
            } else {
                $value['template_id'] = $wechat_app->template_message->addTemplate($value['template_id_short'])['template_id'];
                WxTemplate::updateOrCreate(['template_id_short' => $value['template_id_short']], $value);
            }
        }
        $all = WxTemplate::all()->toArray();
        echo '<pre>';print_r($all);exit;
    }
}
