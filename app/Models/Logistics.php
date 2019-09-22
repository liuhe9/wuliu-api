<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Logistics\Status\{StartLogisticsStatus, ConfirmLogisticsStatus, InTransitLogisticsStatus, ArrivedLogisticsStatus, FinishedLogisticsStatus};

class Logistics extends BaseModel
{
    use SoftDeletes;
    protected $table = 'logisticses';
    public $logisticsStatus;

    public static function boot() {
        parent::boot();
        static::retrieved(function($model) {
            switch($model->status) {
                case StartLogisticsStatus::STATUS_CODE: // 发货填单
                    $model->logisticsStatus = new StartLogisticsStatus();
                    break;
                case ConfirmLogisticsStatus::STATUS_CODE: // 发货确认
                    $model->logisticsStatus = new ConfirmLogisticsStatus();
                    break;
                case InTransitLogisticsStatus::STATUS_CODE: // 发货中
                    $model->logisticsStatus = new InTransitLogisticsStatus();
                    break;
                case ArrivedLogisticsStatus::STATUS_CODE: // 到场结束
                    $model->logisticsStatus = new ArrivedLogisticsStatus();
                    break;
                case FinishedLogisticsStatus::STATUS_CODE: // 收货确认
                    $model->logisticsStatus = new FinishedLogisticsStatus();
                    break;
            }
        });
    }

    public function confirm()
    {
        $this->logisticsStatus->confirm($this);
    }

    public function inTransit()
    {
        $this->logisticsStatus->inTransit($this);
    }

    public function arrived()
    {
        $this->logisticsStatus->arrived($this);
    }

    public function finished()
    {
        $this->logisticsStatus->finished($this);
    }

    public function setConfirm()
    {
        $this->logisticsStatus = new ConfirmLogisticsStatus();
        $this->status = ConfirmLogisticsStatus::STATUS_CODE;
    }

    public function setInTransit()
    {
        $this->logisticsStatus = new InTransitLogisticsStatus();
        $this->status = InTransitLogisticsStatus::STATUS_CODE;
    }

    public function setArrived()
    {
        $this->logisticsStatus = new ArrivedLogisticsStatus();
        $this->status = ArrivedLogisticsStatus::STATUS_CODE;
    }

    public function setFinished()
    {
        $this->logisticsStatus = new FinishedLogisticsStatus();
        $this->status = FinishedLogisticsStatus::STATUS_CODE;
    }
}
