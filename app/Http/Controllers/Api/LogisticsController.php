<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Logistics as LogisticsResource;
use App\Http\Resources\LogisticsCollection;
use App\Http\Resources\LogisticsDriverCollection;
use Illuminate\Http\Request;
use App\Models\Logistics;
use App\Models\LogisticsDriver;
use App\Models\Logistics\Status\{StartLogisticsStatus, ConfirmLogisticsStatus, InTransitLogisticsStatus, ArrivedLogisticsStatus, FinishedLogisticsStatus};
use Illuminate\Support\Facades\Auth;

class LogisticsController extends BaseController
{
    public function index(Request $request)
    {
        $where  = [];
        $status = $request->input('status', -1);
        if ($status != -1) {
            $where['status'] = $status;
        }

        $role = Auth::payload()->get('role');
        $model = '';
        if ($role == 'driver') {
            $where['logistics_drivers.driver_id'] = Auth::id();
            $model = Logistics::where($where)
                    ->leftJoin('logistics_drivers', 'logisticses.tracking_no', '=', 'logistics_drivers.tracking_no')
                    ->select('logisticses.*')
                    ->orderBy('id', 'desc')->paginate();
        } else if ($role == 'consigner') {
            $where['consigner_id'] = Auth::id();
            $model = Logistics::where($where)->orderBy('id', 'desc')->paginate();
        } else {
            $model = Logistics::where($where)->orderBy('id', 'desc')->paginate();
        }

        return new LogisticsCollection($model);
    }

    public function show($id)
    {
        return new LogisticsResource(Logistics::findOrFail($id));
    }

    public function store(Request $request)
    {
        $attributes = [
            'tracking_no'     => $request->input('tracking_no'),
            'product_desc'    => $request->input('product_desc') ?? '',
            'note'            => $request->input('note') ?? '',
            'images'          => json_encode($request->input('images') ?? []),
            'finish_images'   => json_encode([]),
            'consigner_id'    => Auth::id(),
            'receiver_name'   => $request->input('receiver_name'),
            'receiver_mobile' => $request->input('receiver_mobile'),
            'from_address'    => $request->input('from_address'),
            'from_gps'        => $request->input('from_gps'),
            'to_address'      => $request->input('to_address'),
            'to_gps'          => $request->input('to_gps'),
        ];
        $logistics = Logistics::create($attributes);
        return new LogisticsResource($logistics);
    }

    public function myStatus()
    {
        $all_status = Logistics::getAllStatus();
        return $all_status;
    }

    public function status($id, Request $request)
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

    public function gps(Request $request)
    {
        $where = [
            // 'logisticses.status'          => InTransitLogisticsStatus::STATUS_CODE,
            'logistics_drivers.driver_id' => Auth::id(),
        ];
        $model = Logistics::where($where)
                ->leftJoin('logistics_drivers', 'logisticses.tracking_no', '=', 'logistics_drivers.tracking_no')
                ->select('logistics_drivers.id')->get();
        if (!empty($model)) {
            $ids = array_column($model->toArray(), 'id');
            LogisticsDriver::whereIn('id', $ids)->update(['latest_gps' => $request->input('gps')]);
        }
        return $model->toArray();
    }


    public function setDrivers($id, Request $request)
    {
        $logistics = Logistics::findOrFail($id);
        $drivers   = $request->get('drivers');
        foreach($drivers as $value) {
            LogisticsDriver::updateOrCreate(['tracking_no' => $logistics->tracking_no, 'driver_id' => $value]);
        }
        LogisticsDriver::where('tracking_no', '=', $logistics->tracking_no)->whereNotIn('driver_id', $drivers)->delete();
        return $drivers;
    }

    public function statistics(Request $request)
    {
        echo '<pre>';print_r($request->all());exit;
    }
}
