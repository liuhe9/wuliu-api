<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class LogisticsDriver extends JsonResource
{
    /**
     * 将资源转换成数组。
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'tracking_no'   => $this->tracking_no,
            'driver_id'     => $this->driver_id,
            'license_plate' => $this->license_plate,
            'latest_gps'    => $this->latest_gps,
            'created_at'    => Carbon::parse($this->created_at)->toDateTimeString(),
            'updated_at'    => Carbon::parse($this->updated_at)->toDateTimeString(),
        ];
    }
}
