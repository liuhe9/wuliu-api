<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Comany as CompanyResource;
use Illuminate\Http\Request;
use App\Models\Comany;

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
