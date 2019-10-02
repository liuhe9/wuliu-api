<?php

namespace App\Models\Logistics\Status;

use App\Models\Logistics;

class ConfirmLogisticsStatus implements LogisticsStatus
{
    const STATUS_CODE = 1;
    const STATUS_NAME = '已确认';

    function confirm(Logistics $logistics)
    {
        throw new \Exception(self::STATUS_NAME.'不能设置确认');
    }

    function inTransit(Logistics $logistics)
    {
        $logistics->setInTransit();
    }

    function arrived(Logistics $logistics)
    {
        throw new \Exception(self::STATUS_NAME.'不能设置到达');
    }

    function finished(Logistics $logistics)
    {
        throw new \Exception(self::STATUS_NAME.'不能设置已完成');
    }
}
