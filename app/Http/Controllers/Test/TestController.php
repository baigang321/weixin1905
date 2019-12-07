<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
class TestController extends Controller
{
   	public function hello(){
		echo "hello work aa 123 901123";
	}
    public function redis1(){
        $key="weixin";
        $val="hello world";
        Redis::set($key,$val);
        echo date("Y-m-d H:i:s");
    }
}
