<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ToolsController extends Controller
{
    public function index(Request $request)
    {
        $tools_type = $request->input('tools_type', 'qrcode');
        return view('tools'.$tools_type, ['tools_type' => $tools_type]);
    }

    public function store(Request $request)
    {
        $tools_type = $request->input('tools_type');
        switch($tools_type) {
            case 'qrcode':
            $content = $request->input('content');
            $res = Qrcode::encoding('UTF-8')->size(200)->generate($content);
            break;
        }
        return $res;
    }
}
