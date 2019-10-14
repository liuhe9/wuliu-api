<?php

namespace App\Http\Controllers\Api;

use App\Events\LogisticsArrived;
use App\Events\LogisticsConfirm;
use App\Events\LogisticsFinished;
use App\Events\LogisticsInTransit;
use App\Events\LogisticsSetDrivers;
use App\Events\LogisticsStart;
use App\Http\Resources\Logistics as LogisticsResource;
use App\Http\Resources\LogisticsCollection;
use Illuminate\Http\Request;
use App\Models\Logistics;
use App\Models\LogisticsDriver;
use App\Models\Logistics\Status\{ConfirmLogisticsStatus, InTransitLogisticsStatus, ArrivedLogisticsStatus, FinishedLogisticsStatus};
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

use function GuzzleHttp\json_encode;

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
        event(new LogisticsStart($logistics));
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
            case ConfirmLogisticsStatus::STATUS_CODE: // 发货确认
                $logistics->confirm();
                event(new LogisticsConfirm($logistics));
                break;
            case InTransitLogisticsStatus::STATUS_CODE: // 发货中
                $logistics->inTransit();
                event(new LogisticsInTransit($logistics));
                break;
            case ArrivedLogisticsStatus::STATUS_CODE: // 到场结束
                $logistics->arrived();
                event(new LogisticsArrived($logistics));
                break;
            case FinishedLogisticsStatus::STATUS_CODE: // 收货确认
                $logistics->finished();
                event(new LogisticsFinished($logistics));
                break;
        }
        $logistics->save();
        return response()->json(['status' => true]);
    }

    public function gps(Request $request)
    {
        $where = [
            'logistics_drivers.driver_id' => Auth::id(),
        ];
        $model = Logistics::where($where)
                ->whereIn('logisticses.status',[InTransitLogisticsStatus::STATUS_CODE, ArrivedLogisticsStatus::STATUS_CODE])
                ->leftJoin('logistics_drivers', 'logisticses.tracking_no', '=', 'logistics_drivers.tracking_no')
                ->select('logistics_drivers.id')->get();
        if (!empty($model)) {
            $ids = array_column($model->toArray(), 'id');
            LogisticsDriver::whereIn('id', $ids)->update(['latest_gps' => $request->input('gps')]);
        }
        return $model->toArray();
    }

    public function setImages($id, Request $request)
    {
        $logistics  = Logistics::findOrFail($id);
        $images     = $request->input('images');
        $image_type = $request->input('image_type');
        $result     = $logistics->update([$image_type => json_encode($images)]);
        return response()->json(['status' => $result]);
    }

    public function setDrivers($id, Request $request)
    {
        $logistics = Logistics::findOrFail($id);
        $drivers   = $request->get('drivers');
        foreach($drivers as $value) {
            LogisticsDriver::updateOrCreate(['tracking_no' => $logistics->tracking_no, 'driver_id' => $value]);
        }
        LogisticsDriver::where('tracking_no', '=', $logistics->tracking_no)->whereNotIn('driver_id', $drivers)->delete();
        event(new LogisticsSetDrivers($logistics));
        return $drivers;
    }

    public function statistics(Request $request)
    {
        $type = $request->input('type');
        $page = $request->input('page', 1);
        $first = Logistics::first();
        if (empty($first)) {
            return response()->json(['data' => []], 200);
        } else {
            $today      = Carbon::make(date('Y-m-d'));
            $first_day  = Carbon::make($first->created_at->toDateString());
            $diff_day   = $today->diffInDays($first_day);
            $diff_month = $today->diffInMonths($first_day);
            $perPage    = (new Logistics())->getPerPage();
            $diff_key   = 'diff_'.$type;
            $sub_key    = $type =='day' ? 'subDay': 'subMonth';
            $add_key    = $type =='day' ? 'addDay': 'addMonth';
            $sub_key_s  = $sub_key.'s';
            $format_key = 'Y-m-'.($type =='day' ? 'd' : '01');

            if (($page-1) * $perPage > $$diff_key + 1) {
                return response()->json(['data' => []]);
            }
            $meta = [
                'current_page' => $page,
                'per_page'     => $perPage,
                'total'        => $$diff_key + 1,
            ];

            $start_days = ($page-1)*$perPage;
            $end_days   = ($page*$perPage >= $meta['total']) ? $meta['total'] - 1 : $page*$perPage - 1;
            $start_day  = $today->$sub_key_s($start_days);
            $kv         = [];
            for($i = 0; $i <= $end_days - $start_days; $i++) {
                $start_day_clone = clone $start_day;
                $start_day_prev  = $start_day_clone->$add_key()->format($format_key);
                $current_day     = $start_day->format($format_key);
                $kv[] = [
                    'date'  => $type =='day' ? $current_day : substr($current_day, 0, -3),
                    'total' => Logistics::whereBetween('created_at', [$current_day, $start_day_prev])->count(),
                ];
                $start_day->$sub_key();
            }
            $return_data = [
                'data' => $kv,
                'meta' => $meta,
            ];
            return response()->json($return_data);
        }
    }
}
