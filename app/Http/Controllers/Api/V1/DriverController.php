<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Driver as DriverResource;
use App\Http\Resources\DriverCollection;
use Illuminate\Http\Request;
use App\Models\Driver;

class DriverController extends BaseController
{
    public function index()
    {
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
        ];
        $driver = Driver::create($attributes);
        return new DriverResource($driver);
    }

    public function patch($id, Request $request)
    {
        $driver = Driver::findOrFail($id);
        $attributes = array_filter($request->only('name', 'mobile', 'id_card'));

        if ($attributes) {
            $driver->update($attributes);
        }

        return new DriverResource($driver);
    }
}
