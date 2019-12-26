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

Route::get('/info', function () {
	phpinfo();
});

Route::get('/','Index\IndexController@index');  //网址首页


Route::get('/test/hello','Test\TestController@hello');

Route::any('user/index','User\\LoginController@index');
Route::any('user/addUser','User\\LoginController@addUser');
Route::any('user/redis1','User\\LoginController@redis1');
Route::any('user/redis2','User\\LoginController@redis2');
Route::any('user/baidu','User\\LoginController@baidu');
Route::get('/dev/redis/del','VoteController@delKey');

//微信开发
//Route::get('/wx','WeiXin\WxController@wx');
//Route::post('/wx','WeiXin\WxController@receiv');


//微信开发
Route::get('/wx','WeiXin\WxController@wx');
Route::post('/wx','WeiXin\WxController@receiv');         //接受微信推送事件
Route::get('/wx/media','WeiXin\WxController@getMedia');  //获取临时素材
Route::get('/wx/test','WeiXin\WxController@test');       //获取临时素材
Route::get('/wx/menu','WeiXin\WxController@createMenu'); //创建菜单

Route::post('/wx','WeiXin\WxController@receiv');        //接收微信的推送事件
Route::get('/wx/media','WeiXin\WxController@getMedia');        //获取临时素材
Route::get('/wx/flush/access_token','WeiXin\WxController@flushAccessToken');        //刷新access_token
Route::get('/wx/menu','WeiXin\WxController@createMenu');        //创建菜单
//微信公众号
Route::get('/vote','VoteController@index');        //微信投票
Route::get('/goods/detail','Goods\IndexController@detail');        //详情
Route::get('/wx/qrcode','WeiXin\WxQRController@qrcode');

//课程
Route::get('/course/index','Course\CourseController@index');

