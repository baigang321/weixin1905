<?php

namespace App\Http\Controllers\WeiXin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class WxController extends Controller
{
    protected $access_token;
    public function  __construct()
    {
       //获取access_token
        $this->access_token=$this->getAccessToken();
    }
    public  function  getAccessToken(){
        $url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&'.env("WX_APPID").'=&secret='.env("WX_APPSECRET");
        file_put_contents("aaaa.log",$url,FILE_APPEND);
        $data_json=file_get_contents($url);
        $arr=json_decode($data_json,true);
        return $arr['access_token'];
    }
    //处理接入
    public function wechat()
    {
        $token = '12259b56f5898cd6192c50';       //开发提前设置好的 token
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $echostr = $_GET["echostr"];
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if ($tmpStr == $signature) {        //验证通过
            print_r($echostr);
        } else {
            die("not ok");
        }
    }
    //接收微信推送事件
    public  function  receiv(){
        $log_file="wx.log";
        $xml_str=file_get_contents("php://input");
        //将接收的"数据记录到日志文件
        $data=date("Y-m-d H:i:s").$xml_str;
        file_put_contents($log_file,$data,FILE_APPEND);
        //处理xml数据
        $xml_obj=simplexml_load_string($xml_str);
        $event=$xml_obj->Event; //类型
        if($event=='subscribe'){
            $openid=$xml_obj->FromUserName;    //获取用户的openid
            $url='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->access_token.'&openid='.$openid.'&lang=zh_CN';
            $user_info=file_get_contents($url);
            file_put_contents("wx_user.log",$user_info,FILE_APPEND);
        }
    }

    //获取用户基本信息
    public  function  getUserInfo($access_token,$openid){
     $url='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
     //发送网络请求
        $json_str=file_get_contents($url);
        $log_file='wx_user.log';
        file_put_contents($log_file,$json_str,FILE_APPEND);
    }
}