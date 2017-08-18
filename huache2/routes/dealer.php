<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/10
 * Time: 11:24
 */

/**
 * 经销商用户中心路由组
 */

Route::group(['namespace' => 'Dealer', 'as' => 'dealer.', 'middleware' => 'lpss'], function () {
    // 会员中心
    Route::get('/', ['as' => 'ucenter', 'uses' => 'DealerController@getIndex']);
    Route::get('login', ['as' => 'login', 'uses' => 'DealerLoginController@getlogin']);
    Route::post('login', ['as' => 'login', 'uses' => 'DealerLoginController@postlogin']);
    Route::get('loginout', ['as' => 'loginout', 'uses' => 'DealerLoginController@getLogout']);
    Route::post('sendmobilecode', ['as' => 'sendcodetologin', 'uses' => 'DealerLoginController@sendMobileCode']);
    Route::post('postloginbyphone', ['as' => 'postloginbyphone', 'uses' => 'DealerLoginController@postLoginByPhone']);

    Route::get('member_info/{type?}', ['as' => 'member_info', 'uses' => 'DealerController@memberInfo']);
    Route::post('member_info/{type?}', ['as' => 'member_info', 'uses' => 'DealerController@memberInfoPost']);
    Route::get('modify_password', ['as' => 'modify_password', 'uses' => 'DealerController@modifyPassword']);
    Route::post('modify_password_check', ['as' => 'modify_password_check', 'uses' => 'DealerController@modifyPasswordCheck']);
    Route::get('modify_password_input', ['as' => 'modify_password_input', 'uses' => 'DealerController@modifyPasswordInput']);
    Route::post('changepassword', ['as' => 'changepassword', 'uses' => 'DealerController@changePassword']);
    Route::get('changepasswordsuccess', ['as' => 'changepasswordsuccess', 'uses' => 'DealerController@changePasswordSuccess']);

    Route::get('changemobile/{type}', ['as' => 'getchangemobile', 'uses' => 'DealerController@changeMobile']);
    Route::post('changemobile/{type}', ['as' => 'postchangemobile', 'uses' => 'DealerController@postChangeMobile']);
    Route::get('changeemail/{type}', ['as' => 'getchangeemail', 'uses' => 'DealerController@changeEmail']);
    Route::post('changeemail/{type}', ['as' => 'postchangeemail', 'uses' => 'DealerController@postChangeEmail']);
    Route::get('editdealer/{type}/{id}/{step?}', ['as' => 'editdealer', 'uses' => 'DealerController@editDealer']);
    Route::get('del-dealer/{daili_dealer_id}/{dealer_id}', ['as' => 'del-dealer', 'uses' => 'DealerController@realDeleteDealer']);
    Route::post('editdealer/{type}/{id}/{step?}', ['as' => 'posteditdealer', 'uses' => 'DealerController@postEditDealer']);
    Route::post('ajaxsubmitdealer/{type}/{dealer_id?}', ['as' => 'ajaxsubmitdealer', 'uses' => 'DealerController@ajaxSubmitDealer']);
    Route::get('getdealerlist/{city_id}/{brand?}', ['as' => 'getdealerlist', 'uses' => 'DealerController@getDealerList']);
    Route::get('changebank/{type}', ['as'=> 'getchangebank', 'uses'=> 'DealerController@getChangeBack']);
    Route::post('changebank/{type}', ['as'=> 'postchangebank', 'uses '=> 'DealerController@postChangeBack']);
    //经销商车型路由
    Route::get('carmodel/{id}',['as'=>'carmodel','uses'=>'CarmodelController@getCarmodel']);
    Route::get('carmodel/{type}/{dealer_id}/{staple_id?}',['as'=>'editcarmodel','uses'=>'CarmodelController@editCarmodel']);
    //常用管理-常用车型-车源锁定
    Route::get('locking_car_staple/{dealer_id}/{staple_id}/{brand_id}',['as'=>'locking_car_staple','uses'=>'CarmodelController@locking_car_staple']);
    
    Route::get('ajaxcarrmodel/list',['as'=>'ajaxcarmodellist','uses'=>'CarmodelController@getMessage']);
    Route::post('ajaxcarmodel/{type}',['as'=>'ajaxcarmodel','uses'=>'CarmodelController@ajaxcarmodel']);
    Route::get('custorfile/{id}',['as'=>'custorfile','uses'=>'CustormerController@getCustfile']);
    Route::get('work_time/{id}',['as'=>'worktime','uses'=>'CustormerController@getWorkTime']);
    Route::post('work_time',['as'=>'postworktime','uses'=>'CustormerController@postWorkTime']);
    Route::post('ajaxCustfile/{type}',['as'=>'ajaxcustfile','uses'=>'CustormerController@ajaxCustfile']);
    Route::get('surance/{id}',['as'=>'surance','uses'=>'CarmodelController@getSurance']);
    Route::post('addcarmodel/{id}',['as'=>'addcarmodel','uses'=>'CarmodelController@AddCarmodel']);
    Route::post('editcarmodel/{id}',['as'=>'editcars','uses'=>'CarmodelController@editCarMess']);
    //常用管理-工作时段-取消休息日程
    Route::post('cancel_rest_day',['as'=>'cancel_rest_day','uses'=>'CustormerController@cancel_rest_day']);


    //报价步骤get
    Route::get('baojia/ajax-get-data/{type}/',['as'=>'baojia.ajax.get.data','uses'=>'DealerBaojiaController@ajaxGetData']);
    Route::get('baojia/{type}/{id}/{step?}',['as'=>'baojia','uses'=>'DealerBaojiaController@getBaojiaInfo']);
    //报价异步获取
    Route::post('baojia/ajaxsubmit/{type}',['as'=>'baojia.ajax.post','uses'=>'DealerBaojiaController@ajaxSubmit']);
    //报价步骤post
    Route::post('baojia/{type}/{id}/{step?}',['as'=>'postbaojia','uses'=>'DealerBaojiaController@postBaojia']);

    //报价列表
    Route::get('baojialist/{type}',['as'=>'baojialist','uses'=>'DealerBaojiaController@getBaojiaList']);

    //订单列表
    Route::get('orderlist/{type}', ['as'=>'orderlist','uses'=>'DealerOrderController@index']);
    Route::post('order/range','DealerOrderController@getCarRange');
    Route::get('order/orderlist/{type}','DealerOrderController@serchResults');
    Route::get('orderlist/actives/{id}', ['as'=>'activelist','uses'=>'DealerOrderController@showOrder']);
    Route::post('orderlist/actives/{id}',['as'=>'orderstore','uses'=>'DealerOrderController@store']);

    Route::post('order/endoreder/{id}',['as'=>'endoreder','uses'=>'DealerOrderController@setEndOreder']);
    Route::post('order/nonfactory',['as'=>'nonfactory','uses'=>'DealerOrderController@storeNonFactory']);
    Route::any('order/jiaoche','DealerOrderController@storeJaoche');
    Route::post('order/jiaoche/{id}',['as'=>'stoporder_two','uses'=>'DealerOrderController@storeStop']);
    Route::post('order/editjiaoche/{id}',['as'=>'store_edit','uses'=>'DealerOrderController@editJaoche']);
    Route::post('order/special/{id}',['as'=>'store_special','uses'=>'DealerOrderController@storeSpecial']);

    Route::post('order/getcode','DealerOrderController@getCode');

    Route::get('parts/{id}','DealerAccessoryController@show')->name('access.list');
    Route::post('parts/store/{id}','DealerAccessoryController@store')->name('access.store');
    Route::post('appoint/{id}','DealerOrderController@storeAppoint')->name('appoint.store');
    Route::post('delivery/{id}','DealerOrderController@storeDelivery')->name('delivery.store');
    Route::post('deal/{id}','DealerOrderController@storeDeal')->name('deal.store');
    ###==========================================协商
    Route::post('consult/{order}', 'DealerOrderController@storeConsult')->name('deal.consult');

    ####---------------------订单总详情-----------------------------------------
    Route::get('order_detail/{order_id}','DealerParticularController@index')->name('order.detail');

//    Route::get('pay',['as'=>'daili.pay','uses'=>'DealerController@pay']);//商家支付首页

    ###--------------------- 代理商资金 ----------------------------------------
    Route::get('prices/settlement/{any?}','PricesController@showSettlement')->name('settlement')->middleware('auth.seller')->where(['any' => '.*']);
    Route::get('prices/{type?}','PricesController@getIndex')->name('funds')->middleware('auth.seller');//资金
//    Route::get('prices/{type?}','PricesController@getIndex')->name('funds')->middleware(['auth.seller','withdraw.filter:dealer']);
    Route::post('prices/recharge_voucher','PricesController@showRecharge_voucher')->name('rechargeVoucher')->middleware('auth.seller');
    Route::post('prices/application','PricesController@showApplication')->name('applicationWithdrawal')->middleware(['auth.seller','withdraw.filter:dealer']);
//   API
    Route::post('settlement/{any?}','SettlementController@index')->name('api_settlement');
//    Route::post('settlement/mail_file','SettlementController@mail_file')->name('mail_file');
//    Route::post('settlement/mail_history','SettlementController@mail_history')->name('mail_history');
//    Route::post('settlement/cancel_mail','SettlementController@cancel_mail')->name('cancel_mail');
    ###-------------------- end 代理商资金 ----------------------------------------

});
Route::get('options/{dealer}/brand/{brand}/{type?}','Orders\GuaranteesController@getNotOptions');

