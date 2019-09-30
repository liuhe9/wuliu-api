<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Comany as CompanyResource;
use App\Models\Comany;
use Illuminate\Http\Request;

class CompanyController extends BaseController
{
    public function index()
    {
        return new CompanyResource(Comany::all()->take(1));
    }

    public function store(Request $request)
    {
        $attributes = [
            'mobile'  => $request->get('mobile'),
            'name'    => $request->get('name'),
        ];
        $comany = Comany::create($attributes);
        return new CompanyResource($comany);
    }
}
