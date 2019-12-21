<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});
Route::get('/','Index\IndexController@index');
Route::get('/info',function(){
    phpinfo();
});
Route::get('/test/redis1/','Test\TestController@redis1');
Route::get('/test/hello','Test\TestController@hello');
Route::get('/test/adduser','User\LoginController@addUser');
Route::get('/test/adduserdo','User\LoginController@adduserdo');
Route::get('/test/delete/{id}','User\LoginController@destroy');
Route::get('/test/update/{id}','User\LoginController@destroy');

Route::get('/test/xml','Test\TestController@xmlTest');
//微信开发
Route::get("/wx/test","WeiXin\WxController@test");

Route::get('/wx','WeiXin\WxController@wechat');
Route::post('/wx','WeiXin\WxController@receiv'); //接收微信事件
Route::get("/wx/media",'WeiXin\WxController@getMedia');
Route::get('/wx/flush/access_token','WeiXin\WxController@flushAccessToken');
Route::get('/wx/menu','WeiXin\WxController@createMenu');        //创建菜单

//微信公众号
Route::get('/vote','VoteController@index');        //微信投票
Route::get("/goods/detail","Goods\IndexController@detail");
Route::get("/wx/sendMsg","WeiXin\WxController@sendMsg");