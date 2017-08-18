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

/**
 * 前端路由组
 */
Route::group(['namespace' => 'Front', 'middleware' => ['lpss','request.add']], function () {
    /**
     * 首页
     * index
     */
    Route::get('/', ['as' => '/', 'uses' => 'IndexController@index']);
    /**
     * 搜索
     * s
     */
    Route::get('s', ['as' => 'searchQuery', 'uses' => 'ListController@getIndex']);
    Route::get('so', ['as' => 'searchAjax', 'uses' => 'ListController@getAjax']);
    /**
     * 获取地区json数据
     */
    Route::get('area/json', 'ListController@getJsonSecArea');

    //缓存地区\车型
    Route::get('cache/{type}', 'IndexController@excuteCache');

    //web图片验证码生成
    Route::get('makecode', 'IndexController@makeCode')->name('makecode');
    //web图形验证码验证
    Route::get('checkcode','IndexController@checkCode')->name('checkcode');


    // 获取品牌json数据
    Route::get('brand/{id}', 'IndexController@getbrand');
    // 省市联动城市json
    Route::get('getcityjosn/{id}', 'AjaxController@getCity');
    // 提交争议的和解信息
    Route::post('postmediate', 'AjaxController@postMediate');

    //车辆报价对应的车辆 落地计算器
    Route::get('car_calc/{id}/{area_id}/{buytype?}', ['as' => 'car_calc', 'uses' => 'ShowController@getCarCalc']);
    /**
     * 车型报价详情页
     * car
     */
    Route::get('show/{urls}', ['as' => 'car.show', 'uses' => 'ShowController@getShow',]);

    //---------------------------------上线版本----------------------------------
    if (env('APP_ENV') == 'product') {
        Route::get('/', ['as' => '/', function() {
            $date = date("Y,m,d,H,i,s", time());
            return view('index.index_online')->with('date', $date);
        }]);
    }
});

Route::group(['namespace' => 'Front','middleware'=>['auth','request.add']], function(){
    Route::get('show/{urls}/{buytype}', ['as' => 'car.show', 'uses' => 'ShowController@getShow']);
    Route::post('cartone/{data}', ['as' => 'cartOne', 'uses' => 'CartController@oneIndex']);
    //选装精品列表
    Route::get('jingpinList/{id}','ShowController@jingpinList');
    Route::get('cart/editcart/{id}', ['as' => 'cart.editcar', 'uses' => 'CartController@showOrder']);
    Route::post('cart/{type}/{id}', ['as' => 'cart.status', 'uses' => 'CartController@storeStatus']);
    Route::get('cart/pay/{order_sn}', ['as' => 'cart.pay', 'uses' => 'CartController@payGuarantee']);
    Route::post('cart/endorder', ['as' => 'cart.end', 'uses' => 'CartController@endOrder']);
    Route::post('cart/accept',['as'=>'cart.accept','uses'=>'CartController@storeAccept']);
    Route::post('cart/pays', [ 'as'=>'cart.pay.store','uses' => 'CartController@storeSecurity']);
    Route::get('cart/getcode','CartController@getCode')->name('member.getcode');
    Route::post('order/continue/{id}/{type}',['as'=>'store_continue','uses'=>'CartController@storeContinue']);

    //选装件模块
    Route::get('parts/buy/{order_id}', ['as' => 'buy.show', 'uses' => 'CarAccessoryController@showParts']);
    Route::post('parts/store/{order_id}', ['as' => 'buy.store', 'uses' => 'CarAccessoryController@storeParts']);
    Route::get('parts/list/{order_id}', ['as' => 'parts.list', 'uses' => 'CarAccessoryController@listParts']);
    Route::get('parts/negotia/{order_id}', ['as' => 'parts.negotia', 'uses' => 'CarAccessoryController@getNegotia']);
    Route::post('parts/edit/{order_id}', ['as' => 'parts.edit', 'uses' => 'CarAccessoryController@editNegotia']);
    Route::get('parts/getcode','CarAccessoryController@getCode')->name('cart.member.sendSms');
    Route::post('parts/checkcode','CarAccessoryController@checkCode');
    Route::get('parts/logs/{order_id}',['as'=>'parts.logs','uses'=>'CarAccessoryController@getNegotiaLog']);

    Route::post('carts/reply/{id}',['as'=>'store.Reply','uses'=>'CartController@storeReply']);
    Route::post('carts/again/{id}',['as'=>'store.Again','uses'=>'CartController@storeAgain']);
    Route::post('carts/deal/{id}',['as'=>'store.deal','uses'=>'CartController@storeDeal']);
    Route::post('carts/refund/{id}',['as'=>'store.Refund','uses'=>'CartController@storeRefundPrice']);
    //协商
    Route::post('negotiaion/{order}',['as'=>'store.negotiation','uses'=>'CartController@storeNegotiation']);

    //订单详情页
    Route::get('cart/order_detail/{id}',['as'=>'cart.order_detail','uses'=>'CartParticularController@index']);
    Route::post('set/comment/{order}',['as'=>'store.Comment','uses'=>'CartController@storeComment']);
});

//配置详情图片
Route::get('/img/{img}', function ($img) {
    $img = base64_decode($img);
    return view('img')->with('img', $img);
});

