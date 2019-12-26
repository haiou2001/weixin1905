<?php

namespace App\Http\Controllers\WeiXin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\WeiXinModel;
use GuzzleHttp\Client;

class WxQRController extends Controller
{
    public function qrcode(){
        $scene_id=$_GET['scene'];

        $access_token=WeiXinModel::getAccessToken();

        //第一步 获取ticket
        $url="https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token";
        //{"expire_seconds": 604800, "action_name": "QR_STR_SCENE", "action_info": {"scene": {"scene_str": "test"}}}
        $data1=[
            'expire_seconds' =>604800,
            'action_name'    =>'QR_STR_SCENE',
            'action_info'    =>[
                'scene'      =>[
                    'scene_str' =>$scene_id
                ]
            ]
        ];

        $client = new Client();
        $response = $client->request('POST',$url,[
            'body'=>json_encode($data1)
        ]);

        $json1= $response->getBody();
        $tiket =json_decode($json1,true)['ticket'];
        //第二步 获取带参数的二维码
        $url='https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$tiket;
        return redirect($url);
    }

}