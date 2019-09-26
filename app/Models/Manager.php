<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Manager extends BaseModel
{
    // 软删除和用户验证attempt
    use SoftDeletes;

    // 查询用户的时候，不暴露密码
    protected $hidden = ['password'];
}
