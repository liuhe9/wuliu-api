<?php

namespace App\Models\Logistics\Status;

use App\Models\Logistics;

class InTransitLogisticsStatus implements LogisticsStatus
{
    const STATUS_CODE = 20;
    const STATUS_NAME = '运输中';

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
        $logistics->setArrived();
    }

    function finished(Logistics $logistics)
    {
        throw new \Exception(self::STATUS_NAME.'不能设置已完成');
    }
}
