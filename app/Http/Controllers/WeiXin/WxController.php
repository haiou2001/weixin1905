<?php

namespace App\Http\Controllers\WeiXin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Model\WeiXinModel;
use Illuminate\Support\Facades\Redis;
use GuzzleHttp\Client;

class WxController extends Controller
{
    protected $access_token;

    public function __construct()
    {
        //获取sccess_token
        $this->access_token = $this->GetAccessToken();
    }

    public function test()
    {
        echo $this->access_token;
    }

    public function GetAccessToken()
    {

        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WX_APPID').'&secret='.env('WX_APPSECREET');
        $data_json = file_get_contents($url);
        $arr = json_decode($data_json,true);

        return $arr['access_token'];
    }

    //接入微信
    public function wx()
    {
        $token = '2259b56f5898cd6192c50d338723d9e4';
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $echostr = $_GET['echostr'];

        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);


        if ($tmpStr == $signature) {
            echo $echostr;
        } else {
            die('not ok');
        }
    }


        public function receiv()
        {
            $log_file = "wx.log";       // public
            //将接收的数据记录到日志文件
            $xml_str = file_get_contents("php://input");
            $data = date('Y-m-d H:i:s')  . ">>>>>>\n" . $xml_str . "\n\n";
            file_put_contents($log_file,$data,FILE_APPEND);     //追加写
            //处理xml数据
            $xml_obj = simplexml_load_string($xml_str);
            $event = $xml_obj->Event;       // 获取事件类型
            $openid = $xml_obj->FromUserName;       //获取用户的openid
            if($event=='subscribe'){
                //判断用户是否已存在
                $u = WeiXinModel::where(['openid'=>$openid])->first();
                if($u){
                    $msg = '欢迎回来';
                    $xml = '<xml>
  <ToUserName><![CDATA['.$openid.']]></ToUserName>
  <FromUserName><![CDATA['.$xml_obj->ToUserName.']]></FromUserName>
  <CreateTime>'.time().'</CreateTime>
  <MsgType><![CDATA[text]]></MsgType>
  <Content><![CDATA['.$msg.']]></Content>
</xml>';
                    echo $xml;
                }else{
                    //获取用户信息 zcza
                    $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->access_token.'&openid='.$openid.'&lang=zh_CN';
                    $user_info = file_get_contents($url);       //
                    $u = json_decode($user_info,true);
                    //echo '<pre>';print_r($u);echo '</pre>';die;
                    //入库用户信息
                    $user_data = [
                        'openid'    => $openid,
                        'nickname'  => $u['nickname'],
                        'sex'       => $u['sex'],
                        'headimgurl'    => $u['headimgurl'],
                        'subscribe_time'    => $u['subscribe_time']
                    ];
                    //openid 入库
                    $uid = WeiXinModel::insertGetId($user_data);
                    $msg = "谢谢关注";
                    //回复用户关注
                    $xml = '<xml>
  <ToUserName><![CDATA['.$openid.']]></ToUserName>
  <FromUserName><![CDATA['.$xml_obj->ToUserName.']]></FromUserName>
  <CreateTime>'.time().'</CreateTime>
  <MsgType><![CDATA[text]]></MsgType>
  <Content><![CDATA['.$msg.']]></Content>
</xml>';
                    echo $xml;
                }
            }elseif($event=='CLICK'){           // 菜单点击事件
                if($xml_obj->EventKey=='weather'){
                    //如果是 获取天气
                    //请求第三方接口 获取天气
                    $weather_api = 'https://free-api.heweather.net/s6/weather/now?location=beijing&key=d957029d5931428f8eef6ba241aefdd7';
                    $weather_info = file_get_contents($weather_api);
                    $weather_info_arr = json_decode($weather_info,true);
                    $cond_txt = $weather_info_arr['HeWeather6'][0]['now']['cond_txt'];
                    $tmp = $weather_info_arr['HeWeather6'][0]['now']['tmp'];
                    $wind_dir = $weather_info_arr['HeWeather6'][0]['now']['wind_dir'];
                    $msg = $cond_txt . ' 温度： '.$tmp . ' 风向： '. $wind_dir;
                    $response_xml = '<xml>
  <ToUserName><![CDATA['.$openid.']]></ToUserName>
  <FromUserName><![CDATA['.$xml_obj->ToUserName.']]></FromUserName>
  <CreateTime>'.time().'</CreateTime>
  <MsgType><![CDATA[text]]></MsgType>
  <Content><![CDATA['. date('Y-m-d H:i:s') .  $msg .']]></Content>
</xml>';
                    echo $response_xml;
                }
            }
            // 判断消息类型
            $msg_type = $xml_obj->MsgType;
            $touser = $xml_obj->FromUserName;       //接收消息的用户openid
            $fromuser = $xml_obj->ToUserName;       // 开发者公众号的 ID
            $time = time();
            $media_id = $xml_obj->MediaId;
            if($msg_type=='text'){
                $content = date('Y-m-d H:i:s') . $xml_obj->Content;
                $response_text = '<xml>
  <ToUserName><![CDATA['.$touser.']]></ToUserName>
  <FromUserName><![CDATA['.$fromuser.']]></FromUserName>
  <CreateTime>'.$time.'</CreateTime>
  <MsgType><![CDATA[text]]></MsgType>
  <Content><![CDATA['.$content.']]></Content>
</xml>';
                echo $response_text;            // 回复用户消息
                // TODO 消息入库
            }elseif($msg_type=='image'){    // 图片消息
                // TODO 下载图片
                $this->getMedia2($media_id,$msg_type);
                // TODO 回复图片
                $response = '<xml>
  <ToUserName><![CDATA['.$touser.']]></ToUserName>
  <FromUserName><![CDATA['.$fromuser.']]></FromUserName>
  <CreateTime>'.time().'</CreateTime>
  <MsgType><![CDATA[image]]></MsgType>
  <Image>
    <MediaId><![CDATA['.$media_id.']]></MediaId>
  </Image>
</xml>';
                echo $response;
            }elseif($msg_type=='voice'){          // 语音消息
                // 下载语音
                $this->getMedia2($media_id,$msg_type);
                // TODO 回复语音
                $response = '<xml>
  <ToUserName><![CDATA['.$touser.']]></ToUserName>
  <FromUserName><![CDATA['.$fromuser.']]></FromUserName>
  <CreateTime>'.time().'</CreateTime>
  <MsgType><![CDATA[voice]]></MsgType>
  <Voice>
    <MediaId><![CDATA['.$media_id.']]></MediaId>
  </Voice>
</xml>';
                echo $response;
            }elseif($msg_type=='video'){
                // 下载小视频
                $this->getMedia2($media_id,$msg_type);
                // 回复
                $response = '<xml>
  <ToUserName><![CDATA['.$touser.']]></ToUserName>
  <FromUserName><![CDATA['.$fromuser.']]></FromUserName>
  <CreateTime>'.time().'</CreateTime>
  <MsgType><![CDATA[video]]></MsgType>
  <Video>
    <MediaId><![CDATA['.$media_id.']]></MediaId>
    <Title><![CDATA[测试]]></Title>
    <Description><![CDATA[不可描述]]></Description>
  </Video>
</xml>';
                echo $response;
            }
        }
        /**
         * 获取用户基本信息
         */
        public function getUserInfo($access_token,$openid)
        {
            $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
            //发送网络请求
            $json_str = file_get_contents($url);
            $log_file = 'wx_user.log';
            file_put_contents($log_file,$json_str,FILE_APPEND);
        }
        /**
         * 获取素材
         */
        public function getMedia()
        {
            $media_id = 'MvV4Gy3hH5uSB4XJyYj1apLi-_2xVPEf4eyfg_CWpiEOjhnmIkQOZ5uvxOW1d-8D';
            $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$this->access_token.'&media_id='.$media_id;
            //获取素材内容
            $data = file_get_contents($url);
            // 保存文件
            $file_name = date('YmdHis').mt_rand(11111,99999) . '.amr';
            file_put_contents($file_name,$data);
            echo "下载素材成功";echo '</br>';
            echo "文件名： ". $file_name;
        }
        protected function getMedia2($media_id,$media_type)
        {
            $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$this->access_token.'&media_id='.$media_id;
            //获取素材内容
            $client = new Client();
            $response = $client->request('GET',$url);
            //获取文件扩展名
            $f = $response->getHeader('Content-disposition')[0];
            $extension = substr(trim($f,'"'),strpos($f,'.'));
            //获取文件内容
            $file_content = $response->getBody();
            // 保存文件
            $save_path = 'wx_media/';
            if($media_type=='image'){       //保存图片文件
                $file_name = date('YmdHis').mt_rand(11111,99999) . $extension;
                $save_path = $save_path . 'imgs/' . $file_name;
            }elseif($media_type=='voice'){  //保存语音文件
                $file_name = date('YmdHis').mt_rand(11111,99999) . $extension;
                $save_path = $save_path . 'voice/' . $file_name;
            }elseif($media_type=='video')
            {
                $file_name = date('YmdHis').mt_rand(11111,99999) . $extension;
                $save_path = $save_path . 'video/' . $file_name;
            }
            file_put_contents($save_path,$file_content);
        }
        /**
         * 刷新 access_token
         */
        public function flushAccessToken()
        {
            $key = 'wx_access_token';
            Redis::del($key);
            echo $this->getAccessToken();
        }
        /**
         * 创建自定义菜单
         */
        public function createMenu()
        {
            $urls ='http://wjk.xx20.top/vote';
            $urls2 ='http://wjk.xx20.top';
            $redirect_uri = urlencode($urls);        //授权后跳转页面
            $redirect_uri2 = urlencode($urls2);        //授权后跳转页面
            //创建自定义菜单的接口地址
            $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$this->access_token;
            $menu = [
                'button'    => [
                    [
                        'type'  => 'click',
                        'name'  => '获取天气',
                        'key'   => 'weather'
                    ],
                    [
                        'type'  => 'view',
                        'name'  => '投票',
                        'url'   => 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxbb1432093d0e71c4&redirect_uri='.$redirect_uri.'&response_type=code&scope=snsapi_userinfo&state=ABCD1905#wechat_redirect'
                    ],
                    [
                        'type'  => 'view',
                        'name'  => '商城',
                        'url'   => 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxbb1432093d0e71c4&redirect_uri='.$redirect_uri2.'&response_type=code&scope=snsapi_userinfo&state=ABCD1905#wechat_redirect'
                    ],
                ]
            ];
            $menu_json = json_encode($menu,JSON_UNESCAPED_UNICODE);
            $client = new Client();
            $response = $client->request('POST',$url,[
                'body'  => $menu_json
            ]);
            echo '<pre>';print_r($menu);echo '</pre>';
            echo $response->getBody();      //接收 微信接口的响应数据
        }

}
