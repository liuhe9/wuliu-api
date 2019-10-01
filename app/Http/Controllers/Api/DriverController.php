<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Driver as DriverResource;
use App\Http\Resources\DriverCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Driver;

class DriverController extends BaseController
{
    public function index()
    {
        $user = auth()->userOrFail();
        echo '<pre>';print_r($user);exit;
        return new DriverCollection(Driver::orderBy('id', 'desc')->paginate());
    }

    public function show($id)
    {
        return new DriverResource(Driver::findOrFail($id));
    }

    public function store(Request $request)
    {
        $attributes = [
            'mobile'  => $request->get('mobile'),
            'name'    => $request->get('name'),
            'id_card' => $request->get('id_card'),
            'api_token' => Str::random(60),
        ];
        $driver = Driver::create($attributes);
        return $driver->toArray();
    }

    public function patch($id, Request $request)
    {
        $driver = Driver::findOrFail($id);
        $attributes = array_filter($request->only('name', 'mobile', 'id_card'));

        if ($attributes) {
            if ($attributes['mobile'] != $driver->mobile) {
                $attributes['api_token'] = Str::random(60);
                $attributes['openid']    = '';
                $attributes['avatar']    = '';
                $attributes['nickname']  = '';
            }
            $driver->update($attributes);
        }

        return new DriverResource($driver);
    }
}
