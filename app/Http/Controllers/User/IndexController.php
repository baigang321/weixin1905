<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function sign1(){
    	  echo '<pre>';print_r($_GET);echo '</pre>';
    	  $sign=$_GET['sign'];
    	  unset($_GET['sign']);
    	  ksort($_GET);
    	  echo '<pre>';print_r($_GET);echo '</pre>';
    	 //拼接 代签名 字符串
        $str="";
        foreach ($_GET as $k => $v) {
           $str.=$k.'='.$v.'&';
        }
        //拼接字符串
        $str=rtrim($str,'&');
        echo $str;echo '<hr>';

        //使用公钥验签
        $pub_key=file_get_contents(storage_path('keys/pubkey2'));
        $status=openssl_verify($str,base64_decode($sign),$pub_key,OPENSSL_ALGO_SHA256);
        var_dump($status);
        if($status){
        	echo "success";
        }else{
        	echo "失败";
        }
    }
    public function sign2(){
        $sign_token='abcdefg';
        echo '<pre>';print_r($_GET);echo '</pre>';
        $sign1=$_GET['sign'];
         echo "发送端接收的签名: ".$sign1;
        unset($_GET['sign']);
        ksort($_GET);
        echo '<pre>';print_r($_GET);echo '</pre>';
        $str="";
        foreach ($_GET as $k => $v) {
              $str.=$k.'='.$v.'&';
        }
         $str=rtrim($str,'&');
         echo "待签名的字符串: ".$str;
        //计算签名
        echo "</br>";
        $sign2=md5($str.$sign_token);
        echo "接收端接收的签名: ".$sign2;
         echo "</br>";
        if($sign1===$sign2){
            echo "验签成功";
        }else{
            echo "验签失败";
        }
    }
}
