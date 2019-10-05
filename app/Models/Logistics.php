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

    public function getNextStatusAttribute() {
        $all_status = self::getAllStatus();
        $next_status = null;
        $last_status = end($all_status);
        reset($all_status);
        while(current($all_status)) {
            if(key($all_status) == $this->status && $this->status != $last_status) {
                next($all_status);
                $next_status = current($all_status);
            } else {
                next($all_status);
            }
        }
        return $next_status;
    }

    public function getStatusNameAttribute() {
        return $this->logisticsStatus->getName();
    }

    public function drivers() {
        return $this->hasMany('App\Models\LogisticsDriver', 'tracking_no', 'tracking_no');
    }

    public function consignerInfo() {
        return $this->hasOne('App\Models\Consigner', 'id', 'consigner_id');
    }

    public static function getAllStatus()
    {
        return [
            StartLogisticsStatus::STATUS_CODE => [
                'name' => StartLogisticsStatus::STATUS_NAME,
                'code' => StartLogisticsStatus::STATUS_CODE,
            ],
            ConfirmLogisticsStatus::STATUS_CODE => [
                'name' => ConfirmLogisticsStatus::STATUS_NAME,
                'code' => ConfirmLogisticsStatus::STATUS_CODE,
            ],
            InTransitLogisticsStatus::STATUS_CODE => [
                'name' => InTransitLogisticsStatus::STATUS_NAME,
                'code' => InTransitLogisticsStatus::STATUS_CODE,
            ],
            ArrivedLogisticsStatus::STATUS_CODE => [
                'name' => ArrivedLogisticsStatus::STATUS_NAME,
                'code' => ArrivedLogisticsStatus::STATUS_CODE,
            ],
            FinishedLogisticsStatus::STATUS_CODE => [
                'name' => FinishedLogisticsStatus::STATUS_NAME,
                'code' => FinishedLogisticsStatus::STATUS_CODE,
            ],
        ];
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
