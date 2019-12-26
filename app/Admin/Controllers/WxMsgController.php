<?php

namespace App\Admin\Controllers;

use App\Model\WeiXinModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use GuzzleHttp\Client;;

class WxMsgController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '微信用户管理';

    public function sendMsg()
    {
        $access_token=WeiXinModel::getAccessToken();
        $openid_arr=WeiXinModel::select('openid','nickname','sex')->get()->toArray();
//        echo '<pre>';print_r($openid_arr);echo '</pre>';
        $openid=array_column($openid_arr,'openid');
        echo '<pre>';print_r($openid);echo '</pre>';
        $url='https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token='.$access_token;
        $msg=date('Y-m-d H:i:s').'哈喽小老弟';
        $data=[
            'touser'  => $openid,
            'msgtype' => 'text',
            'text'    => ['content'=>$msg]
        ];
        $client=new Client();
        $response=$client->request('POST',$url,[
            'body' => json_encode($data,JSON_UNESCAPED_UNICODE)
        ]);
        echo $response->getBody();
    }
}
