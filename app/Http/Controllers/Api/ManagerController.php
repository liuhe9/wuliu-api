<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Manager as ManagerResource;
use App\Http\Resources\ManagerCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Manager;
use Illuminate\Support\Facades\Auth;

class ManagerController extends BaseController
{
    public function index()
    {
        return new ManagerCollection(Manager::orderBy('id', 'desc')->paginate());
    }

    public function me()
    {
        $id = Auth::id();
        return new ManagerResource(Manager::findOrFail($id));
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
            'api_token' => Str::random(60),
        ];
        $manager = Manager::create($attributes);
        return response()->json($manager);;
    }

    public function patch($id,Request $request)
    {
        $manager    = Manager::findOrFail($id);
        $attributes = array_filter($request->only('name', 'mobile'));

        if ($attributes) {
            if ($attributes['mobile'] != $manager->mobile) {
                $attributes['api_token'] = Str::random(60);
                $attributes['openid']    = '';
                $attributes['avatar']    = '';
                $attributes['nickname']  = '';
            }

            $manager->update($attributes);
        }

        return new ManagerResource($manager);
    }
}
