<?php

namespace App\Http\Controllers;

use App\Events\LogisticsArrived;
use App\Events\LogisticsConfirm;
use App\Events\LogisticsFinished;
use App\Events\LogisticsInTransit;
use App\Events\LogisticsSetDrivers;
use App\Events\LogisticsStart;
use App\Models\Logistics;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $info = Logistics::find(16);
        // event(new LogisticsSetDrivers($info));
    }
}
