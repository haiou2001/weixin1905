<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Model\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use GuzzleHttp\Client;
class LoginController extends Controller
{
    public function addUser()
    {
        $pass = '13245json';
        $email = 'lisi@qq.com';
        //使用密码函数
        $password = password_hash($pass,PASSWORD_BCRYPT);
        $data = [
           'user_name' => 'zhangsan',
           'password' =>  $password,
            'email' => $email,
        ];

        $uid = UserModel::insertGetId($data);
        var_dump($uid);
    }

    public function redis1()
    {
        $key = '19050';
        $val = 'hello weixin';
        Redis::set($key,$val);
        echo date('Y-m-d H:i:s');
    }

    public function redis2()
    {
        $key = 'weixin';
        echo Redis::set($key,$val);
    }

    //请求百度
    public function baidu()
    {
        $url = 'http://www.ifeng.com/';
        $client = new Client();
        $response = $client->request('GET',$url);
        echo $response->getBody();
    }

//    2259b56f5898cd6192c50d338723d9e4
}
