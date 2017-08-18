<?php
/**
 * [$origin description] 设置ajax 跨域访问
 * @var [type]
 */
$origin = isset($_SERVER['HTTP_ORIGIN'])? $_SERVER['HTTP_ORIGIN'] : '';
$expect= ['http://admin.123.com','http://admin.hwache.cn','http://admin.hwache.com'];
if(in_array($origin, $expect)){
    header("Access-Control-Allow-Origin:".$origin);
}

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//后台调用短信发送接口
Route::get('sendTestSms','ApiTestController@sendSms')->name('test.sendSms');
Route::get('test','ApiTestController@test');

Route::match(['get','post'],'jxbTestArbitrate','ApiTestController@jxbArbitrate')->name('test.jxb');
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->post('token', 'App\Api\v1\Controllers\ApiController@token');    //获取                                                                                                                token
    $api->post('refresh-token', 'App\Api\v1\Controllers\ApiController@refreshToken);                                                                                                                en'); //刷新token
    $api->group(['middleware' => 'auth:api'], function ($api) {
        $api->get('users/{id}', 'App\Api\v1\Controllers\ApiController@info');
    });
    $api->post('/daili_account/{id}/check_too_low', 'App\Api\v1\Controllers\DailiAccountController@check_too_low');
    $api->post('/daili_account/{id}/set_too_low_time', 'App\Api\v1\Controllers\DailiAccountController@set_too_low_time');
});

