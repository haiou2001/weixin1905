<?php

namespace App\Http\Controllers\WeiXin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\WeiXinModel;

class WxController extends Controller
{
    protected $access_token;

    public function __construct()
    {
        //获取sccess_token
        $this->access_token = $this->GetAccessToken();
    }

    public function GetAccessToken(){
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WX_APPID').'&secret='.env('WX_APPSECREET');
        $data_json = file_get_contents($url);
        $arr = json_decode($data_json,true);
        return $arr['access_token'];
    }

    //接入微信
    public function wx(){
        $token='2259b56f5898cd6192c50d338723d9e4';
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $echostr=$_GET['echostr'];

        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );


        if($tmpStr == $signature){
            echo $echostr;
        }else{
            die('not ok');
        }

    }

    /*
   * 接收微信推送事件
   */
//    public function receiv()
//    {
//        $log_file = "wx.log";       // public
//        //将接收的数据记录到日志文件
//        $xml_str = file_get_contents("php://input");
//        $data = date('Y-m-d H:i:s')  . ">>>>>>\n" . $xml_str . "\n\n";
//        file_put_contents($log_file,$data,FILE_APPEND);     //追加写
//        //处理xml数据
//        $xml_obj = simplexml_load_string($xml_str);
//        $event = $xml_obj->Event;       // 获取事件类型
//        if($event=='subscribe'){
//            $openid = $xml_obj->FromUserName;       //获取用户的openid
//            //判断用户是否已存在
//            $u = WeiXinModel::where(['openid'=>$openid])->first();
//            if($u){
//                $msg = '欢迎回来';
//                $xml = '<xml>
//                      <ToUserName><![CDATA['.$openid.']]></ToUserName>
//                      <FromUserName><![CDATA['.$xml_obj->ToUserName.']]></FromUserName>
//                      <CreateTime>'.time().'</CreateTime>
//                      <MsgType><![CDATA[text]]></MsgType>
//                      <Content><![CDATA['.$msg.']]></Content>
//                </xml>';
//                echo $xml;
//            }else{
//                //获取用户信息 zcza
//                $url ="https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->access_token.'&openid='.$openid.'&lang=zh_CN";
//
//                $user_info = file_get_contents($url);       //
//                $u = json_decode($user_info,true);
//
//
////                echo '<pre>';print_r($u);echo '</pre>';die;
//                //入库用户信息
//                $user_data = [
//                    'openid'    => $openid,
//                    'nickname'  => $u['nickname'],
//                    'sex'       => $u['sex'],
//                    'headimgurl'    => $u['headimgurl'],
//                    'subscribe_time'    => $u['subscribe_time']
//                ];
//
//                //openid 入库
//                $uid = WeiXinModel::insertGetId($user_data);
//                $msg = "谢谢关注";
//                //回复用户关注
//                $xml = '<xml>
//                  <ToUserName><![CDATA['.$openid.']]></ToUserName>
//                  <FromUserName><![CDATA['.$xml_obj->ToUserName.']]></FromUserName>
//                  <CreateTime>'.time().'</CreateTime>
//                  <MsgType><![CDATA[text]]></MsgType>
//                  <Content><![CDATA['.$msg.']]></Content>
//                </xml>';
//                echo $xml;
//            }
//        }
//        // 判断消息类型
//        $msg_type = $xml_obj->MsgType;
//        $touser = $xml_obj->FromUserName;       //接收消息的用户openid
//        $fromuser = $xml_obj->ToUserName;       // 开发者公众号的 ID
//        $time = time();
//        if($msg_type=='text'){
//            $content = date('Y-m-d H:i:s') . $xml_obj->Content;
//            $response_text = '<xml>
//  <ToUserName><![CDATA['.$touser.']]></ToUserName>
//  <FromUserName><![CDATA['.$fromuser.']]></FromUserName>
//  <CreateTime>'.$time.'</CreateTime>
//  <MsgType><![CDATA[text]]></MsgType>
//  <Content><![CDATA['.$content.']]></Content>
//</xml>';
//            echo $response_text;            // 回复用户消息
//        }
//    }


    public function receiv()
    {
        $log_file = 'wx.log';
        $xml_str = file_get_contents("php://input");
        //将接收的数据记录到日志文件
        $data = date('Y-m-d H:i:s') . $xml_str;
        file_put_contents($log_file, $data, FILE_APPEND);         //追加写
        //处理xml数据
        $xml_obj = simplexml_load_string($xml_str);
        //获取TOKEN
        $access_token = $this->GetAccessToken();
        //调用微信用户信息
        $yonghu = $this->getUserInfo($access_token, $xml_obj->FromUserName);
        //转换用户信息
        $userInfo = json_decode($yonghu, true);
        //打印用户信息
//        dd($userInfo);
        if ($xml_obj->MsgType == 'event') {
            $event = $xml_obj->Event;  //获取事件7类型 是不是关注
            if ($event == 'subscribe') {
                $oppenid = $xml_obj->FromUserName;   //获取用户的oppenid
                $user_data = [
                    'openid' => $oppenid,
                    'subscribe_time' => $xml_obj->CreateTime,
                    'nickname' => $userInfo['nickname'],
                    'sex' => $userInfo['sex']
                ];
                $u = WeiXinModel::where(['openid' => $oppenid])->first();
                if ($u) {
                    $this->huifu($xml_obj, 3, $userInfo['nickname']);
                } else {
                    //入库
                    $uid = WeiXinModel::insertGetId($user_data);
                    $this->huifu($xml_obj, 2, $userInfo['nickname']);
                }
            }

        }

        $msg_type = $xml_obj->MsgType;
        if ($msg_type == 'text') {
            $this->huifu($xml_obj, 1, $userInfo['nickname']);

        }
    }
    /**
     *获取用户基本信息
     */
    public function getUserInfo($access_token,$oppenid){
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$oppenid.'&lang=zh_CN';
        //发送网络请求
        $json_str = file_get_contents($url);
        $log_file = 'wx.user.log';
        file_put_contents($log_file,$json_str,FILE_APPEND);
    }

    public function huifu($xml_obj, $code, $nickname)
    {
        $time = time();
        $touser = $xml_obj->FromUserName;  //接受用户的oppenid
        $fromuser = $xml_obj->ToUserName;   //开发者公众号的id

        if ($code == 1) {
            $content = "您好 " . $nickname . " 现在北京时间" . date('Y-m-d H:i:s') . "   " . $xml_obj->Content;
        } elseif ($code == 2) {
            $content = "您好 " . $nickname . " 现在北京时间" . date('Y-m-d H:i:s') . "   " . "欢迎关注";
        } elseif ($code == 3) {
            $content = "您好 " . $nickname . " 现在北京时间" . date('Y-m-d H:i:s') . "   " . "欢迎回来";
        }

        $response_text = '<xml>
        <ToUserName><![CDATA[' . $touser . ']]></ToUserName>
        <FromUserName><![CDATA[' . $fromuser . ']]></FromUserName>
        <CreateTime>' . $time . '</CreateTime>
        <MsgType><![CDATA[text]]></MsgType>
        <Content><![CDATA[' . $content . ']]></Content>
</xml>';
        echo $response_text;            // 回复用户消息
    }
}
