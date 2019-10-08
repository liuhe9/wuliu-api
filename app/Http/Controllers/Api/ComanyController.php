<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Company as CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends BaseController
{
    public function index()
    {
        return Company::first();
    }

    public function store(Request $request)
    {
        $attributes = [
            'images'  => $request->get('images'),
        ];
        Company::create($attributes);
        return $attributes;
    }

    public function patch($id, Request $request)
    {
        $company = Company::findOrFail($id);
        $attributes = [
            'images'  => $request->get('images'),
        ];

        $company->update($attributes);
        return $company;
    }
}
