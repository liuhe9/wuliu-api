<?php

namespace App\Models\Logistics\Status;

use App\Models\Logistics;

interface LogisticsStatus
{
    public function getName(); // 获取名字
    public function confirm(Logistics $logistics); // 确认
    public function inTransit(Logistics $logistics); // 发货中
    public function arrived(Logistics $logistics);  // 到达
    public function finished(Logistics $logistics);  // 完成
}
