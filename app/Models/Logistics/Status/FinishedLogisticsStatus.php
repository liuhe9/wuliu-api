<?php

namespace App\Models\Logistics\Status;

use App\Models\Logistics;

class FinishedLogisticsStatus implements LogisticsStatus
{
    const STATUS_CODE = 40;
    const STATUS_NAME = '完成';

    function getName() {
        return self::STATUS_NAME;
    }

    function confirm(Logistics $logistics)
    {
        throw new \Exception(self::STATUS_NAME.'不能设置确认');
    }

    function inTransit(Logistics $logistics)
    {
        throw new \Exception(self::STATUS_NAME.'不能设置运输中');
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
