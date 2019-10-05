<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogisticsDriver extends Model
{
    protected $fillable = ['tracking_no', 'driver_id'];

    public function driver() {
        return $this->hasOne('App\Models\Driver', 'id', 'driver_id');
    }
}
