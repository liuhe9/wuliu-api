<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $guarded = ['id'];
    protected $dates = ['created_at', 'updated_at'];
    protected $perPage = 20;
}
