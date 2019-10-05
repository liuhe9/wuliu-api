<?php

namespace App\Http\Resources;

use App\Models\LogisticsDriver;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\Models\Consigner as ConsignerModel;
use App\Models\Manager as ManagerModel;
use App\Models\LogisticsDriver as LogisticsDriverModel;

class Logistics extends JsonResource
{
    /**
     * 将资源转换成数组。
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $drivers = $this->drivers;
        if (!empty($drivers)) {
            foreach($drivers as $key => $driver) {
                $driver->driver;
                $drivers[$key] = $driver;
            }
        }

        return [
            'id'              => $this->id,
            'tracking_no'     => $this->tracking_no,
            'product_desc'    => $this->product_desc,
            'note'            => $this->note,
            'images'          => $this->images,
            'finish_images'   => $this->finish_images,
            'consigner_id'    => $this->consigner_id,
            'consigner_name'  => $this->consignerInfo->name ?? '',
            'consigner_mobile'=> $this->consignerInfo->mobile ?? '',
            'receiver_name'   => $this->receiver_name,
            'receiver_mobile' => $this->receiver_mobile,
            'from_address'    => $this->from_address,
            'from_gps'        => $this->from_gps,
            'to_address'      => $this->to_address,
            'to_gps'          => $this->to_gps,
            'status'          => $this->status,
            'status_name'     => $this->statusName,
            'next_status'     => $this->nextStatus,
            'drivers'         => $drivers,
            'created_at'      => Carbon::parse($this->created_at)->toDateTimeString(),
            'updated_at'      => Carbon::parse($this->updated_at)->toDateTimeString(),
        ];
    }
}
