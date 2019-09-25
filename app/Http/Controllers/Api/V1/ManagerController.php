<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Manager as ManagerResource;
use App\Http\Resources\ManagerCollection;
use Illuminate\Http\Request;
use App\Models\Authorization;
use App\Models\Manager;
use Illuminate\Support\Facades\Redis;

class ManagerController extends BaseController
{
    public function index()
    {
        session(['key' => 'value']);
       echo  session('key');
        return new ManagerCollection(Manager::orderBy('id', 'desc')->paginate());
    }

    public function show($id)
    {
        return new ManagerResource(Manager::findOrFail($id));
    }

    public function store(Request $request)
    {
        $attributes = [
            'mobile'   => $request->get('mobile'),
            'name'     => $request->get('name'),
            'password' => app('hash')->make($request->get('password')),
        ];
        $manager = Manager::create($attributes);
        return new ManagerResource($manager);
    }

    public function patch($id, Request $request)
    {
        $manager    = Manager::findOrFail($id);
        $attributes = array_filter($request->only('name', 'mobile'));

        if ($attributes) {
            $manager->update($attributes);
        }

        return new ManagerResource($manager);
    }
}
