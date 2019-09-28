<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Consigner as ConsignerResource;
use App\Http\Resources\ConsignerCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Consigner;

class ConsignerController extends BaseController
{
    public function index()
    {
        return new ConsignerCollection(Consigner::orderBy('id', 'desc')->paginate());
    }

    public function show($id)
    {
        return new ConsignerResource(Consigner::findOrFail($id));
    }

    public function store(Request $request)
    {
        $attributes = [
            'mobile'  => $request->get('mobile'),
            'name'    => $request->get('name'),
            'api_token' => Str::random(60),
        ];
        $consigner = Consigner::create($attributes);
        return new ConsignerResource($consigner);
    }

    public function patch($id, Request $request)
    {
        $consigner = Consigner::findOrFail($id);
        $attributes = array_filter($request->only('name', 'mobile'));

        if ($attributes) {
            if ($attributes['mobile'] != $consigner->mobile) {
                $attributes['api_token'] = Str::random(60);
                $attributes['openid']    = '';
                $attributes['avatar']    = '';
                $attributes['nickname']  = '';
            }
            $consigner->update($attributes);
        }

        return new ConsignerResource($consigner);
    }
}
