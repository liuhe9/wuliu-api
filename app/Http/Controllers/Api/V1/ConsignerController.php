<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Consigner as ConsignerResource;
use App\Http\Resources\ConsignerCollection;
use Illuminate\Http\Request;
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
        ];
        $consigner = Consigner::create($attributes);
        return new ConsignerResource($consigner);
    }

    public function patch($id, Request $request)
    {
        $consigner = Consigner::findOrFail($id);
        $attributes = array_filter($request->only('name', 'mobile'));

        if ($attributes) {
            $consigner->update($attributes);
        }

        return new ConsignerResource($consigner);
    }
}
