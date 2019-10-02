<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class FileController extends BaseController
{
    public function store(Request $request)
    {
        $date   = date('ymd');
        $floder = $request->input('floder', 'app');
        $path   = $request->file('file')->store($floder.'/'.$date);
        return $path;
    }
}
