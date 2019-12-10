<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function hello()
    {
        echo '海鸥1905';
        echo '涉黑';
    }

    public function xmlTest()
    {
        $xml_str ='<xml>
                    <ToUserName><![CDATA[gh_7f7aecf49bfb]]></ToUserName>
                    <FromUserName><![CDATA[oPA8y0xy1BFImV_wuc7rIfrdWMP8]]></FromUserName>
                    <CreateTime>1575888051</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[aaa]]></Content>
                    <MsgId>22561292412281981</MsgId>
                   </xml>';
        $xml_obj = simplexml_load_string($xml_str);

        echo '<pre>';print_r($xml_obj); echo '</pre>';echo '<hr>';die;
        echo '<pre>';print_r($xml_obj); echo '</pre>';echo '<hr>';

        echo 'ToUserName: '.$xml_obj->ToUserName;echo '<br>';
        echo 'FromUserName: '.$xml_obj->FromUserName;echo '<br>';
    }
}
