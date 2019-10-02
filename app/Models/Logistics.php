<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\Logistics\Status\{StartLogisticsStatus, ConfirmLogisticsStatus, InTransitLogisticsStatus, ArrivedLogisticsStatus, FinishedLogisticsStatus};

class Logistics extends Model
{
    use SoftDeletes;
    protected $table = 'logisticses';
    public $logisticsStatus;
    protected $fillable = ['tracking_no', 'consigner_id', 'receiver_name', 'receiver_mobile', 'product_desc', 'note', 'images', 'from_address', 'from_gps', 'to_address', 'to_gps'];

    public static function boot() {
        parent::boot();
        static::retrieved(function($model) {
            $model->status;
        });
    }

    public function getStatusAttribute($value){
        switch($value) {
            case StartLogisticsStatus::STATUS_CODE: // 发货填单
                $this->logisticsStatus = new StartLogisticsStatus();
                break;
            case ConfirmLogisticsStatus::STATUS_CODE: // 发货确认
                $this->logisticsStatus = new ConfirmLogisticsStatus();
                break;
            case InTransitLogisticsStatus::STATUS_CODE: // 发货中
                $this->logisticsStatus = new InTransitLogisticsStatus();
                break;
            case ArrivedLogisticsStatus::STATUS_CODE: // 到场结束
                $this->logisticsStatus = new ArrivedLogisticsStatus();
                break;
            case FinishedLogisticsStatus::STATUS_CODE: // 收货确认
                $this->logisticsStatus = new FinishedLogisticsStatus();
                break;
        }
        return $value;
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
