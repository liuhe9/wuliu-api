<?php

namespace App\Models\Logistics\Status;

use App\Models\Logistics;

class ConfirmLogisticsStatus implements LogisticsStatus
{
    const STATUS_CODE = 10;
    const STATUS_NAME = '确认';

    function getName() {
        return self::STATUS_NAME;
    }

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
