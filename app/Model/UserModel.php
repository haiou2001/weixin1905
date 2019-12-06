<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    //数据表名
    protected $table = 'user';
    //主键id
    protected $primaryKey = 'uid';
    //时间戳
}
