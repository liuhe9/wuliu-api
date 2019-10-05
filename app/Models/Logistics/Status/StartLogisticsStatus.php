<?php

namespace App\Models\Logistics\Status;

use App\Models\Logistics;

class StartLogisticsStatus implements LogisticsStatus
{
    const STATUS_CODE = 0;
    const STATUS_NAME = '发货填单';

    function getName() {
        return self::STATUS_NAME;
    }

    function confirm(Logistics $logistics)
    {
        $logistics->setConfirm();
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
        throw new \Exception(self::STATUS_NAME.'不能设置完成');
    }
}
