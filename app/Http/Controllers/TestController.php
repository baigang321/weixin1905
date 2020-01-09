<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
class TestController extends Controller
{

    public function alipay()

    {
        $ali_gateway = 'https://openapi.alipaydev.com/gateway.do';  //支付网关
        // 公共请求参数
        $appid = '2016101400681549';

        $method = 'alipay.trade.page.pay';

        $charset = 'utf-8';

        $signtype = 'RSA2';

        $sign = '';

        $timestamp = date('Y-m-d H:i:s');

        $version = '1.0';

        $return_url = 'http://1905api.comcto.com/test/alipay/return';       // 支付宝同步通知

        $notify_url = 'http://1905api.comcto.com/test/alipay/notify';        // 支付宝异步通知地址

        $biz_content = '';

        // 请求参数

        $out_trade_no = time() . rand(1111,9999);       //商户订单号

        $product_code = 'FAST_INSTANT_TRADE_PAY';

        $total_amount = 0.01;

        $subject = '测试订单' . $out_trade_no;
        $request_param = [
            'out_trade_no'  => $out_trade_no,
            'product_code'  => $product_code,
            'total_amount'  => $total_amount,
            'subject'       => $subject
        ];
        $param = [
            'app_id'        => $appid,
            'method'        => $method,
            'charset'       => $charset,
            'sign_type'     => $signtype,
            'timestamp'     => $timestamp,
            'version'       => $version,
            'notify_url'    => $notify_url,
            'return_url'    => $return_url,
            'biz_content'   => json_encode($request_param)
        ];

        //echo '<pre>';print_r($param);echo '</pre>';
        // 字典序排序
        ksort($param);

        //echo '<pre>';print_r($param);echo '</pre>';
        // 2 拼接 key1=value1&key2=value2...
        $str = "";

        foreach($param as $k=>$v)
        {
            $str .= $k . '=' . $v . '&';

        }
        //echo 'str: '.$str;echo '</br>';

        $str = rtrim($str,'&');
        //echo 'str: '.$str;echo '</br>';echo '<hr>';
        // 3 计算签名   https://docs.open.alipay.com/291/106118
        $key = storage_path('keys/app_priv');
        $priKey = file_get_contents($key);

        $res = openssl_get_privatekey($priKey);

        //var_dump($res);echo '</br>';

        openssl_sign($str, $sign, $res, OPENSSL_ALGO_SHA256);

        $sign = base64_encode($sign);

        $param['sign'] = $sign;



        // 4 urlencode

        $param_str = '?';

        foreach($param as $k=>$v){

            $param_str .= $k.'='.urlencode($v) . '&';

        }

        $param_str = rtrim($param_str,'&');

        $url = $ali_gateway . $param_str;

        //发送GET请求

        //echo $url;die;

        header("Location:".$url);
    }

    public function redis1(){
        $priv_key=file_get_contents(storage_path("keys/priv.key"));   
        $data="hello worldss";
        echo "待加密数据:" .$data;echo "</br>";
        openssl_private_encrypt($data, $enc_data, $priv_key);
        echo $enc_data;
        echo "</br>";
        $base64_encode_str=base64_encode($enc_data);
        echo $base64_encode_str;
        echo "</br>";
    }
    //签名测试
      //签名测试
    public function sign1(){
        $params=[
            'username'=>'zhnagsna',
            'email'=>'zhnagsna@qq.com',
            'amount'=>5000,
            'data'=>time()
        ];
        echo '<pre>';print_r($params);echo '</pre>';
        //将参数字典排序
        ksort($params);
         echo '<pre>';print_r($params);echo '</pre>'; 
        //拼接待签名的字符串
        $str="";
        foreach ($params as $k => $v) {
           $str.=$k.'='.$v.'&';
        }
        //拼接字符串
        $str=rtrim($str,'&');
        echo $str;echo '<hr>';
        //使用 私钥进行签名
        $priv_key=file_get_contents(storage_path('keys/priv.key'));
        openssl_sign($str,$signature,$priv_key,OPENSSL_ALGO_SHA256);
        // echo openssl_error_string();die;
        var_dump($signature);
        echo "</br>";

        // //验证签名
        // $pub_key=file_get_contents(storage_path('keys/pub.key'));
        // $status=  openssl_sign($str,$signature,$pub_key,OPENSSL_ALGO_SHA256);
        // var_dump($status);


        //64编码签名
        $sign=base64_encode($signature);
        echo '<hr>';
        echo "签名: ".$sign; echo '<hr>';
        $url="http://weixin.1905.com/sign1?".$str.'&sign='.urlencode($sign);

        echo $url;
    }
    public function sign2(){
        $sign_token='abcdefg';
        $params=[
            'order_id'=>mt_rand(111111,999999),
            'amount'=>9999,
            'uid'=>100,
            'time'=>time()
        ];
        ksort($params);
         $str="";
        foreach ($params as $k => $v) {
           $str.=$k.'='.$v.'&';
        }
        //拼接字符串
        $str=rtrim($str,'&');
        echo $str;echo '<hr>';
        //计算签名
        $tmp_str=$str.$sign_token;
        echo '</br>';
        echo $tmp_str;echo '</br>';
        $sign=md5($tmp_str);
        echo '签名结果: '.$sign;
        echo '</br>';
        $url="http://weixin.1905.com/test?".$str.'&sign='.$sign;
        echo $url;echo '</br>';
    }
}