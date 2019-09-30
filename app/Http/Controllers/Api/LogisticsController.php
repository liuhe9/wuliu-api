<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Logistics as LogisticsResource;
use App\Http\Resources\LogisticsCollection;
use App\Http\Resources\LogisticsDriverCollection;
use Illuminate\Http\Request;
use App\Models\Logistics;
use App\Models\LogisticsDriver;
use App\Models\Logistics\Status\{StartLogisticsStatus, ConfirmLogisticsStatus, InTransitLogisticsStatus, ArrivedLogisticsStatus, FinishedLogisticsStatus};

class LogisticsController extends BaseController
{
    public function index()
    {
        return new LogisticsCollection(Logistics::orderBy('id', 'desc')->paginate());
    }

    public function show($id)
    {
        return new LogisticsResource(Logistics::findOrFail($id));
    }

    public function store(Request $request)
    {
        $attributes = [
            'tracking_no'     => $request->get('tracking_no'),
            'product_desc'    => $request->get('product_desc') ?? '',
            'note'            => $request->get('note') ?? '',
            'images'          => json_encode($request->get('images') ?? []),
            'finish_images'   => json_encode([]),
            'consigner_id'    => $request->get('consigner_id'),
            'receiver_name'   => $request->get('receiver_name'),
            'receiver_mobile' => $request->get('receiver_mobile'),
            'from_address'    => $request->get('from_address'),
            'from_gps'        => $request->get('from_gps'),
            'to_address'      => $request->get('to_address'),
            'to_gps'          => $request->get('to_gps'),
            'status'          => 0,
        ];
        $logistics = Logistics::create($attributes);
        return new LogisticsResource($logistics);
    }

    public function status($id,Request $request)
    {
        $logistics = Logistics::findOrFail($id);
        $status    = $request->get('status');
        switch($status) {
            case StartLogisticsStatus::STATUS_CODE: // 发货填单
                $logistics->start();
                break;
            case ConfirmLogisticsStatus::STATUS_CODE: // 发货确认
                $logistics->confirm();
                break;
            case InTransitLogisticsStatus::STATUS_CODE: // 发货中
                $logistics->inTransit();
                break;
            case ArrivedLogisticsStatus::STATUS_CODE: // 到场结束
                $logistics->arrived();
                break;
            case FinishedLogisticsStatus::STATUS_CODE: // 收货确认
                $logistics->finished();
                break;
        }
        $logistics->save();
        return new LogisticsResource($logistics);
    }

    public function gps($request)
    {

    }

    public function drivers($id,Request $request)
    {
        $logistics = Logistics::findOrFail($id);
        $drivers   = $request->get('drivers');
        $driver_ids = [];
        foreach($drivers as $value) {
            $driver_ids[] = $value['driver_id'];
            LogisticsDriver::updateOrCreate(['tracking_no' => $logistics->tracking_no, 'driver_id' => $value['driver_id']], ['license_plate' => $value['license_plate']]);
        }
        LogisticsDriver::where('tracking_no', '=', $logistics->tracking_no)->whereNotIn('driver_id', $driver_ids)->delete();
        return new LogisticsDriverCollection(LogisticsDriver::where('tracking_no','=', $logistics->tracking_no));
    }


}
