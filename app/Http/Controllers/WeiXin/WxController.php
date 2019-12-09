<?php

namespace App\Http\Controllers\WeiXin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WxController extends Controller
{
    public function wechat()
    {
        $toke = '2259b56f5898cd6192c50d338723d9e4';
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            echo $echostr;
        }else{
            die("not ok");
        }
    }

    //接口
    public function  index(Request $request)
    {
        echo $request->echostr;
    }

    //获取用回基本信息
    public function  getUserInfo()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=ACCESS_TOKEN&openid=OPENID&lang=zh_CN';
    }

    //接收微信推送事件
    public function receiv()
    {
        $log_file = "wx.log";
        //将接收的数据记录到日志文件
        $xml = file_get_contents("php://input");
        $data = date('Y-m-d H:i:s') .$xml;
        file_put_contents($log_file,$data,FILE_APPEND);  //追加写
        
    }
    public function  asda(Request $request)
    {
        echo $request->echostr;
    }
}
