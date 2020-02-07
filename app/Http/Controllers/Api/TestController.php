<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\UserModel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;
use GuzzleHttp\Client;
class TestController extends Controller
{
    public function test(){
    	$user_info=[
    		'uid'=>123,
    		'name'=>'lishi',
    		'email'=>'lishi@qq.com',
    		'age'=>18
    	];

    	$response=[
    		'errno'=>0,
    		'msg'=>'ok',
    		'data'=>[
    			'user_info'=>$user_info,
    		]
    	];
    	echo json_encode($response);
    }
    public function reg0(Request $request){
    	echo '<pre>';print_r($request->input());echo '</pre>';
    	$pass1=$request->input('pass1');
    	$pass2=$request->input('pass2');
    	if($pass1!=$pass2){
    		die("两次输入不一样");
    	}
    	$password=password_hash($pass1,PASSWORD_BCRYPT);
    	$data=[
    		'email'=>$request->input('email'),
    		'name'=>$request->input('name'),
    		'password'=>$password,
    		'mobile'=>$request->input('mobile'),
    		'last_login'=>time(),
    		'last_ip'=>$_SERVER['REMOTE_ADDR'],
    	];
    	$uid=UserModel::insertGetId($data);
    	var_dump($uid);

    }

  	public function login0(Request $request){
  		$name=$request->input('name');
  		$pass=$request->input('pass');
  		//echo "pass: ".$pass;echo '<br>';
  		$u=UserModel::where(['name'=>$name])->first();
  		//var_dump($u);die;
  		if($u){
  			//echo '<pre>';print_r($u);echo '</pre>';
  			//密码
  			if(password_verify($pass,$u->password)){
  				echo "登陆成功";
  				$token=Str::random(32);
  				$response=[
  						'errno'=>0,
  						'msg'=>'ok',
  						'data'=>[
  							'token'=>$token
  						]
  				];
  				
  			}else{
  				$response=[
  					'errno'=>400003,
  					'msg'=>'密码不正确',
  				];
  			}
  			
  		}else{
  			$response=[
  					'errno'=>400004,
  					'msg'=>'没有此用户',
  				];
  		}
		return $response;
  	}
  	public function userList()
    {
        $user_token = $_SERVER['HTTP_TOKEN'];
        echo 'user_token: '.$user_token;echo '</br>';
        $current_url = $_SERVER['REQUEST_URI'];
        echo "当前URL: ".$current_url;echo '<hr>';
        //echo '<pre>';print_r($_SERVER);echo '</pre>';
        //$url = $_SERVER[''] . $_SERVER[''];
        $redis_key = 'str:count:u:'.$user_token.':url:'.md5($current_url);
        echo 'redis key: '.$redis_key;echo '</br>';
        $count = Redis::get($redis_key);        //获取接口的访问次数
        echo "接口的访问次数： ".$count;echo '</br>';
        if($count >= 10){
            echo "请不要频繁访问此接口，访问次数已到上限，请稍后再试";
            Redis::expire($redis_key,10);
            die;
        }
        $count = Redis::incr($redis_key);
        echo 'count: '.$count;
    }
    public function reg(){
      // echo '<pre>';print_r($_POST);echo'</pre>';
      $url='http://1905passport.com/api/user/reg';
      $response = UserModel::curlPost($url,$_POST);
      return $response;
    }
     public function login()
    {
        //请求passport
          // echo '<pre>';print_r($_POST);echo'</pre>';
        $url = 'http://1905passport.com/api/user/login';
        $response = UserModel::curlPost($url,$_POST);
        return $response;
    }
     public function showData()
    {
        // 收到 token
        $uid = $_SERVER['HTTP_UID'];
        $token = $_SERVER['HTTP_TOKEN'];
        // 请求passport鉴权
        $url = 'http://passport.1905.com/api/auth';         //鉴权接口
        $response = UserModel::curlPost($url,['uid'=>$uid,'token'=>$token]);
        $status = json_decode($response,true);
        //处理鉴权结果
        if($status['errno']==0)     //鉴权通过
        {
            $data = "sdlfkjsldfkjsdlf";
            $response = [
                'errno' => 0,
                'msg'   => 'ok',
                'data'  => $data
            ];
        }else{          //鉴权失败
            $response = [
                'errno' => 40003,
                'msg'   => '授权失败'
            ];
        }
        return $response;
    }
    public function postamanl(){

        $data = [
            'user_name' => 'zhangsan',
            'email'     => 'zhangsan@qq.com',
            'amount'    => 10000
        ];
      }
    
    public function md5test()
    {
        $data = "Hello world";      //要发送的数据
        $key = "1905";              //计算签名的key  发送端与接收端拥有相同的key

        //计算签名  MD5($data . $key)
        //$signature = md5($data . $key);
        $signature = 'sdlfkjsldfkjsfd';

        echo "待发送的数据：". $data;echo '</br>';
        echo "签名：". $signature;echo '</br>';

        //发送数据
        $url = "http://1905passport.com/test/check?data=".$data . '&signature='.$signature;
        echo $url;echo '<hr>';

        $response = file_get_contents($url);
        echo $response;
    }


    public function sign2()
    {
        $key = "1905";          
       
        $order_info = [
            "order_id"     => 'LN_' . mt_rand(111111,999999),
            "order_amount" => mt_rand(111,999),
            "uid"  => 12345,
            "add_time"=> time(),
        ];
        $data_json = json_encode($order_info);
        $sign = md5($data_json.$key);
        $client = new Client();
        $url = 'http://1905passport.com/test/check2';
        $response = $client->request("POST",$url,[
            "form_params"   => [
                "data"  => $data_json,
                "sign"  => $sign
            ]
        ]);
        //接收服务器端的数据
        $response_data = $response->getBody();
        echo $response_data;
    }
}
