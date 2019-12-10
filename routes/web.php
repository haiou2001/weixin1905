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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/info', function () {
	phpinfo();
});

Route::get('/test/hello','Test\TestController@hello');

Route::any('user/index','User\\LoginController@index');
Route::any('user/addUser','User\\LoginController@addUser');
Route::any('user/redis1','User\\LoginController@redis1');
Route::any('user/redis2','User\\LoginController@redis2');
Route::any('user/baidu','User\\LoginController@baidu');


//微信开发
Route::any('weixin/wechat','WeiXin\WxController@wechat');
Route::get('weixin/index','WeiXin\WxController@index');
Route::get('/test/xml','Test\TestController@xmlTest');

