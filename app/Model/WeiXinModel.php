<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WeiXinModel extends Model
{

    protected $table = 'p_wx_users';
    //主键id
    protected $primaryKey = 'uid';
}