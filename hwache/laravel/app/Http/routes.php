<?php

/**
 * 前端路由组
 */
Route::group(['namespace' => 'Front', 'middleware' => 'lpss'], function () {
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
    Route::get('makecode', ['as' => 'makecode', 'uses' => 'IndexController@makeCode']);
    //web图形验证码验证
    Route::get('checkcode', ['as' => 'checkcode', 'uses' => 'IndexController@checkCode']);
    
    
    // 获取品牌json数据
    Route::get('brand/{id}', 'IndexController@getbrand');
    // 省市联动城市json
    Route::get('getcityjosn/{id}', 'AjaxController@getCity');
    // 提交争议的和解信息
    Route::post('postmediate', 'AjaxController@postMediate');
    // 登陆
    Route::get('login', ['as' => 'login', 'uses' => 'UserController@getLogin']);
    // Route::get('userlogin',['as'=>'userlogin','uses'=>'UserController@userLogin']);
    Route::get('logout', ['as' => 'logout', 'uses' => 'UserController@Logout']);
    Route::resource('users', 'UserController');
    // 报价对比
    Route::get('compare/{area}/{brand_id}/{bj_ids}/{buy_type}', ['as' => 'compare', 'uses' => 'ListController@Compare']);
    //车辆报价对应的车辆 落地计算器
    Route::get('car_calc/{id}/{area_id}/{buytype}', ['as' => 'car_calc', 'uses' => 'ShowController@getCarCalc']);
    /**
     * 车型报价详情页
     * car
     */
    Route::get('show/{urls}/{buytype}', ['as' => 'car.show', 'uses' => 'ShowController@getShow',]);
   // Route::get('show/{id}/{distance}/{buytype}', ['as' => 'car.show', 'uses' => 'ShowController@getShow',]);
    // 提交平台没有的特殊文件
    Route::get('tellus', ['as' => 'car.tellus', 'uses' => 'ShowController@tellUs',]);
    Route::post('tellus', ['as' => 'car.savetellus', 'uses' => 'ShowController@saveTellUs',]);

    // 代理反馈并修改了车辆信息
    Route::get('cart/editcar/{order_num}', ['as' => 'cart.editcar', 'uses' => 'CartController@editCar',]);
    // 确认修改过的车辆信息
    Route::post('cart/confirmeidt', ['as' => 'car.confirmeidt', 'uses' => 'CartController@confirmEidt',]);
    // 代理没有修改信息，客户接受了特需文件反馈
    Route::get('cart/acceptfile/{order_num}', ['as' => 'cart.acceptfile', 'uses' => 'CartController@acceptFile',]);
    // 代理没有修改信息，客户不接受特需文件反馈
    Route::get('cart/notacceptfile/{order_num}', ['as' => 'cart.notacceptfile', 'uses' => 'CartController@notAcceptFile',]);
    // 代理有修改信息，客户不接受特需文件反馈
    Route::get('cart/acceptedit/{order_num}', ['as' => 'cart.acceptedit', 'uses' => 'CartController@acceptEdit',]);
    // 代理有修改信息，客户接受特需文件反馈,不接受修改
    Route::get('cart/notacceptedit/{order_num}', ['as' => 'cart.notacceptedit', 'uses' => 'CartController@notAcceptEdit',]);
    // 客户接受了特需文件和修改信息
    Route::get('cart/acceptall/{order_num}', ['as' => 'cart.acceptall', 'uses' => 'CartController@acceptAll',]);
    // 被代理终止了订单
    Route::get('cart/stop1/{order_num}', ['as' => 'cart.stop1', 'uses' => 'CartController@stop1',]);
    // 发出交车通知前代理终止了订单
    Route::get('cart/stop2/{order_num}', ['as' => 'cart.stop2', 'uses' => 'CartController@stop2',]);
    // 代理提议修改订单
    Route::get('cart/pdiedit/{order_num}', ['as' => 'cart.pdiedit', 'uses' => 'CartController@pdiEdit',]);
    // 保存客户的的选择
    Route::post('cart/savepdiedit', ['as' => 'cart.savepdiedit', 'uses' => 'CartController@savePdiEdit',]);
    // 接受订单修改，等待发出交车通知
    Route::get('cart/waitnotice/{order_num}', ['as' => 'cart.waitnotice', 'uses' => 'CartController@waitNotice',]);
    // 不接受订单修改，终止订单
    Route::get('cart/stop3/{order_num}', ['as' => 'cart.stop3', 'uses' => 'CartController@stop3',]);
    /**
     * 购买
     * buy
     */
    // Route::post('buy', 'BuyController@index');
    // Route::any('buy', ['as' => 'buy', 'uses' => 'BuyController@index']);
    /**
     * 购物车
     * cart
     */
    // Route::get('cart', ['as' => 'cart', 'uses' => 'CartController@getIndex']);
    // Route::post('cart', ['as' => 'cart', 'uses' => 'CartController@postIndex']);
    Route::post('cartOne', ['as' => 'cartOne', 'uses' => 'CartController@oneIndex']);
    // 预约交车--经销商:消费者
    // Route::get('cart/yuyue/{id}/sell', ['as' => 'cart.yuyue.sell', 'uses' => 'CartController@getYuyueSell']);
    // 预约交车等待
    Route::get('cart/yuyuefirst/{id}', ['as' => 'cart.yuyuefirst', 'uses' => 'CartController@getYuyueFirst']);
    // 预约资料填写
    Route::get('cart/yuyue/{id}', ['as' => 'cart.yuyue', 'uses' => 'CartController@getYuyue']);
    // 保存客户提交的预约信息
    Route::post('cart/yuyue', ['as' => 'cart.yuyue.post', 'uses' => 'CartController@postYuyue']);
    // 客户提交资料后等待代理确认
    // Route::get('cart/yuyueconfirm/{id}', ['as' => 'cart.yuyueconfirm', 'uses' => 'CartController@yuyueConfirm']);
    // 预约反馈不ok
    Route::get('cart/yuyueno/{id}', ['as' => 'cart.yuyueno', 'uses' => 'CartController@yuYueNo']);
    // 预约反馈不ok，再次确认代理提交的条件
    Route::get('cart/yuyuenoconfirm/{id}', ['as' => 'cart.yuyuenoconfirm', 'uses' => 'CartController@yuYueNoConfirm']);
    // 保存客户再次确认的信息
    Route::post('cart/postyuyuenoconfirm', ['as' => 'cart.postyuyuenoconfirm', 'uses' => 'CartController@postYuyueNoConfirm']);
    // 预约反馈不ok，再次反馈ok
    Route::get('cart/yuyueconfirmok/{id}', ['as' => 'cart.yuyueconfirmok', 'uses' => 'CartController@yuYueConfirmOk']);
    // 预约反馈ok
    Route::get('cart/yuyueok/{id}', ['as' => 'cart.yuyueok', 'uses' => 'CartController@yuYueOk']);
    //用户选择安装 原厂非前置选装件  和 非原厂选装件
    Route::get('cart/xzjusergetlist/{order_num}', ['as' => 'cart.xzjusergetlist', 'uses' => 'CartController@xzjUserGetList']);
    // 预约完成后用户选择选装件并提交
    Route::post('cart/xzjpost', ['as' => 'cart.xzjpost', 'uses' => 'CartController@xzjPost']);
    // 预约完成
    Route::get('cart/yuyueend/{id}', ['as' => 'cart.yuyueend', 'uses' => 'CartController@yuYueEnd']);
    // 改变状态，进人提车阶段
    Route::post('cart/gotiche', ['as' => 'cart.gotiche', 'uses' => 'CartController@goTiche']);
    // 付款提车
    Route::get('cart/tiche/{id}', ['as' => 'cart.tiche', 'uses' => 'CartController@getTiche']);
    Route::post('cart/tiche', ['as' => 'cart.tiche.post', 'uses' => 'CartController@postTiche']);
    // 即将交车提醒
    Route::get('cart/tiche_tixing', ['as' => 'cart.tiche_tixing', 'uses' => 'CartController@ticheTixing']);
    // 到交车时间
    Route::get('cart/tiche_now', ['as' => 'cart.tiche_now', 'uses' => 'CartController@ticheNow']);
    // 填写提车信息
    Route::get('cart/tiche_info/{order_num}', ['as' => 'cart.ticheinfo', 'uses' => 'CartController@ticheInfo']);
    // 保存提交车辆信息
    Route::post('cart/saveticheinfo', ['as' => 'cart.saveticheinfo', 'uses' => 'CartController@saveTicheInfo']);
    // 客户提交提车争议
    Route::get('cart/zhengyi/{order_num}', ['as' => 'cart.zhengyi', 'uses' => 'CartController@zhengYi']);
    // 保存客户提交的争议
    Route::post('cart/savezhengyi', ['as' => 'cart.savezhengyi', 'uses' => 'CartController@saveZhengyi']);
    // 争议调查调解
    Route::any('cart/dispute/{order_num}', ['as' => 'cart.dispute', 'uses' => 'CartController@Dispute']);
    // 经销商提交了争议，客户申辩
    Route::any('cart/defend/{order_num}', ['as' => 'cart.defend', 'uses' => 'CartController@Defend']);
    // 接受不接受
    Route::get('cart/acceptmediate/{order_num}/{a}', ['as' => 'cart.acceptmediate', 'uses' => 'CartController@acceptMediate']);
    // 调解成功
    Route::get('cart/mediateok/{order_num}', ['as' => 'cart.mediateok', 'uses' => 'CartController@mediateOk']);
    // 调解失败
    Route::get('cart/mediatefail/{order_num}', ['as' => 'cart.mediatefail', 'uses' => 'CartController@MediateFail']);
    // 交车完成
    Route::get('cart/ticheend/{order_num}', ['as' => 'cart.ticheend', 'uses' => 'CartController@ticheOk']);
    // 交车完成后进行其他信息填充，如果上牌时间或者上牌的相关信息 上牌地区 车辆用途  车主名称 拍照号码 节能补贴发放时间
    Route::post('cart/postotherticheinfo', ['as' => 'cart.postotherticheinfo', 'uses' => 'CartController@postOtherTicheInfo']);
    /*
        退担保金
    */
    // 核实信息
    Route::get('cart/heshi/{order_num}', ['as' => 'cart.heshi', 'uses' => 'CartController@heShi']);
    // 核实信息
    Route::post('cart/heshi', ['as' => 'cart.heshipost', 'uses' => 'CartController@postHeShi']);

    // 办理退款
    Route::get('cart/tuikuan/{order_num}', ['as' => 'cart.tuikuan', 'uses' => 'CartController@tuiKuan']);
    // 退款完毕
    Route::get('cart/tuikuanend/{order_num}', ['as' => 'cart.tuikuanend', 'uses' => 'CartController@tuiKuanEnd']);
    // 完成评价
    Route::get('cart/pingjia/{order_num}', ['as' => 'cart.pingjia', 'uses' => 'CartController@pingJia']);
    // 提交评价
    Route::post('cart/pingjia', ['as' => 'cart.pingjiapost', 'uses' => 'CartController@postPingJia']);
    // 完成评价 展示页面
    Route::get('cart/pingjiaend/{order_num}', ['as' => 'cart.pingjiaend', 'uses' => 'CartController@pingJiaEnd']);
	
    // 代理ajax处理订单
    Route::post('cart/ajax/{order_num}/{type}', ['as' => 'cart.ajaxaction', 'uses' => 'CartController@ajaxAction']);
    /**
     * 支付
     * pay
     */
    // 支付诚意金
    Route::any('pay/earnest/{id}', ['as' => 'pay.earnest', 'uses' => 'PayController@getEarnest']);
    // 支付完诚意金等待卖方确认
    Route::get('pay/wait/{id}', ['as' => 'pay.wait', 'uses' => 'PayController@getWait']);
    // 支付保证金
    Route::any('pay/deposit/{id}', ['as' => 'pay.deposit', 'uses' => 'PayController@getDeposit']);
    // 保证金支付到账等待核实
    Route::get('pay/depositwait/{id}', ['as' => 'pay.depositwait', 'uses' => 'PayController@getDepositWait']);
    // 保证金支付完成
    Route::get('pay/depositok/{order_num}', ['as' => 'pay.depositok', 'uses' => 'PayController@getDepositOK']);
    // 支付成功
    Route::get('pay/success', ['as' => 'pay.success', 'uses' => 'PayController@paySuccess']);

    // 跳转第三方支付
    Route::post('pay/gopay', ['as' => 'pay.gopay', 'uses' => 'PayController@postGopay']);
    // 支付宝支付功能
    Route::get('pay/alipay', ['as' => 'pay.alipay', 'uses' => 'PayController@getAlipay']);
    // 支付宝页面跳转同步通知
    Route::get('pay/alipay/return', ['as' => 'pay.alipay.return', 'uses' => 'PayController@getAlipayReturn']);
    // 支付宝服务器异步通知
    Route::post('pay/alipay/notify', ['as' => 'pay.alipay.notify', 'uses' => 'PayController@postAlipayNotify']);
    // 找回密码
    Route::get('getpwd', ['as' => 'getpassword', 'uses' => 'PasswordController@getpwd']);
    // 用法手机号找回
    Route::get('getpwdbyphone', ['as' => 'getpwdbyphone', 'uses' => 'PasswordController@getpwdbyphone']);
    // 发送手机验证码
    Route::get('sendmobilecode/{tel}', ['as' => 'sendmobilecode', 'uses' => 'PasswordController@sendMobileCode']);

    Route::get('getpwdbyphone2', ['as' => 'getpwdbyphone2', 'uses' => 'PasswordController@getpwdbyphone2']);
    Route::post('getpwdbyphone3', ['as' => 'getpwdbyphone3', 'uses' => 'PasswordController@getpwdbyphone3']);
    // 用邮箱找回
    Route::get('getpwdbyemail', ['as' => 'getpwdbyemail', 'uses' => 'PasswordController@getpwdbyemail']);
    Route::get('getpwdbyemail2/{email}/{c}', ['as' => 'getpwdbyemail2', 'uses' => 'PasswordController@getpwdbyemail2']);
    Route::post('getpwdbyemail3', ['as' => 'getpwdbyemail3', 'uses' => 'PasswordController@getpwdbyemail3']);
    // 发送邮件返回json
    Route::get('sendemail/{email}', ['as' => 'sendemail', 'uses' => 'PasswordController@sendEmail']);
    // 发送成功页面
    Route::get('sm_success', ['as' => 'sm_success', 'uses' => 'PasswordController@sendEmailSuccess']);
    // 手机注册用户
    Route::get('regbyphone', ['as' => 'regbyphone', 'uses' => 'UserController@regbyphone']);
    Route::get('regbyphone2', ['as' => 'regbyphone2', 'uses' => 'UserController@regbyphone2']);
    Route::post('regbyphone3', ['as' => 'regbyphone3', 'uses' => 'UserController@regbyphone3']);
    Route::post('setpwd_phone', ['as' => 'setpwd_phone', 'uses' => 'UserController@setpwd_phone']);
    // 邮箱注册账号
    Route::get('regbyemail', ['as' => 'regbyemail', 'uses' => 'UserController@regbyemail']);
    Route::get('regbyemail2', ['as' => 'regbyemail2', 'uses' => 'UserController@regbyemail2']);
    Route::get('regbyemail3/{email}/{pwd}', ['as' => 'regbyemail3', 'uses' => 'UserController@regbyemail3']);
    Route::get('reg_sendemail/{email}', ['as' => 'reg_sendemail', 'uses' => 'UserController@reg_sendemail']);
    // 发送验证码
    Route::get('sendcode/{tel}', ['as' => 'sendcode', 'uses' => 'UserController@sendCode']);
    // 发送验证码变更手机号码验证
    Route::get('sendcodechangephone/{tel}/{type}', ['as' => 'sendcodechangephone', 'uses' => 'UserController@sendCodeToChangePhone']);

    // 取得验证验进行验证
    Route::get('getcode', ['as' => 'getcode', 'uses' => 'UserController@getCode']);
    
    
    
    // 客户实时查看自己的订单情况
    Route::get('getmyorder/{order_num}', ['as' => 'getmyorder', 'uses' => 'UserController@getMyOrder']);
    // 经销商代理实时查看自己的订单情况
    Route::get('getmyorderdaili/{order_num}', ['as' => 'getmyorderdaili', 'uses' => 'UserController@getMyOrderDaiLi']);
    // 客户查看订单概况
    Route::get('orderoverview/{order_num}', ['as' => 'orderoverview', 'uses' => 'CartController@getOrderOverview']);
    // 保存客户选择的选装件
    Route::post('saveuserxzj', ['as' => 'saveuserxzj', 'uses' => 'UserController@saveUserXzj']);
    /*
    *保险参数及价格计算
    */
    Route::post('postbaoxian', ['as' => 'postbaoxian', 'uses' => 'BaoxianController@index']);

    /*
     * 短信相关功能
     */
    Route::group(['prefix' => 'sms', 'as' => 'sms.'], function () {
        // 普通验证码
        Route::get('code/{mobile}', ['as' => 'code', 'uses' => 'SmsController@show']);
        Route::post('code', ['as' => 'code', 'uses' => 'SmsController@store']);
    });
});

/**
 * 用户中心路由组
 */
Route::group(['namespace' => 'User', 'prefix' => 'user', 'as' => 'user.', 'middleware' => 'lpss'], function () {
    // 会员中心
    Route::get('/', ['as' => 'ucenter', 'uses' => 'UserController@getIndex']);
    // 会员中心 用户个人资料管理
    Route::any('memberInfo', ['as' => 'member_info', 'uses' => 'UserController@memberInfoOp']);

    // 会员中心 安全设置-操作(变量)
    Route::any('memberSafe/{do}', ['as' => 'member_safe', 'uses' => 'UserController@memberSafeOp']);
    //web图片验证码生成
    Route::get('makecode', ['as' => 'makecode', 'uses' => 'UserController@makeCode']);
    //web图形验证码验证
    Route::get('checkcode', ['as' => 'checkcode', 'uses' => 'UserController@checkCode']);
    
    // 会员中心 安全设置-操作(变量)
    Route::any('memberSafe/verify_email/{verify}', ['as' => 'verify_email', 'uses' => 'UserController@getVerifyEmailOp']);

    // 会员中心 通过验证过的邮箱进行更改手机号码
    Route::any('memberSafe/verify_email_to_change_phone/{verify}', ['as' => 'verify_email', 'uses' => 'UserController@getVerifyEmailToChangePhoneOp']);

    // 会员中心 通过验证过的邮箱进行更改密码
    Route::any('memberSafe/verify_change_passwd/{verify}', ['as' => 'verify_change_passwd', 'uses' => 'UserController@getVerifyEmailToChangePwdOp']);
    // 会员中心 银行账户
    Route::any('memberBankAccount', ['as' => 'member_bank_account', 'uses' => 'UserController@memberBankAccountOp']);
    // 会员中心 订单管理
    Route::get('memberOrder', ['as' => 'member_order', 'uses' => 'UserController@memberOrderOp']);
    // 会员中心 我的财务 我的余额
    Route::get('memberFinance', ['as' => 'member_finance', 'uses' => 'UserController@memberFinanceOp']);
    // 会员中心 我的财务 我的提现
    Route::get('memberCash', ['as' => 'member_cash', 'uses' => 'UserController@memberCashOp']);

    // 会员中心 我的财务 我的发票
    Route::any('memberInvoiceList', ['as' => 'member_invoice_list', 'uses' => 'UserController@memberInvoiceListOp']);
    // 会员中心 我的财务 我的发票
    Route::any('memberInvoice/{order_num?}', ['as' => 'member_invoice', 'uses' => 'UserController@memberInvoiceOp']);
    // 会员中心 我的文件
    Route::any('memberFile', ['as' => 'member_file', 'uses' => 'UserController@memberFileOp']);

    // 会员中心 我的文件
    Route::any('memberFile/{excute}/{id?}/', ['as' => 'member_file_edit', 'uses' => 'UserController@memberFileEditOp']);
    // 会员中心 特殊文件下载
    Route::get('memberSpecialFile', ['as' => 'member_special_file', 'uses' => 'UserController@memberSpecialFileOp']);


    // 注册
    Route::group(['prefix' => 'reg', 'as' => 'reg.'], function () {
        // 手机注册
        Route::get('fill_mobile', ['as' => 'fill_mobile', 'uses' => 'RegController@getFillMobile']);
        Route::post('send_code', ['as' => 'send_code', 'uses' => 'RegController@postSendMobileCode']);
        Route::get('get_code', ['as' => 'get_code', 'uses' => 'RegController@getMobileCode']);
        // 邮箱注册
        Route::get('fill_email', ['as' => 'fill_email', 'uses' => 'RegController@getFillEmail']);
        Route::post('send_email', ['as' => 'send_email', 'uses' => 'RegController@postSendEmailLink']);
        Route::get('verify_email/{key}', ['as' => 'verify_email', 'uses' => 'RegController@getVerifyEmail']);
        Route::get('enter_email_sent', ['as' => 'enter_email_sent', 'uses' => 'RegController@getEnterEmailSend']);
        // 设置密码
        Route::get('set_pwd', ['as' => 'set_pwd', 'uses' => 'RegController@getSetPwd']);
        // 保存数据
        Route::post('save_info', ['as' => 'save_info', 'uses' => 'RegController@postSaveInfo']);
        // 注册完成
        Route::get('ok', ['as' => 'ok', 'uses' => 'RegController@getOk']);
        // 重设密码
        Route::get('reset_pwd/{step?}', ['as' => 'reset_pwd', 'uses' => 'RegController@getResetPwd']);
    });

    // 登陆
    Route::get('login', ['as' => 'login', 'uses' => 'LoginController@getlogin']);
    Route::post('login', ['as' => 'login', 'uses' => 'LoginController@postlogin']);
    // 登出
    Route::get('logout', ['as' => 'logout', 'uses' => 'LoginController@getLogout']);

    // 会员个人资料
    Route::group(['prefix' => 'info'], function () {
        Route::get('/', ['as' => 'info', 'uses' => 'InfoController@getInfo']);
    });

    // 资金相关
    Route::group(['prefix' => 'money', 'as' => 'money.'], function () {
        // 用户资金首页
        Route::get('index', ['as' => 'index', 'uses' => 'MoneyController@getIndex']);

        // 充值
        Route::get('topup/{id?}', ['as' => 'topup', 'uses' => 'MoneyController@getTopUp']);
        Route::post('topup', ['as' => 'topup', 'uses' => 'MoneyController@postTopUp']);
        // 单独充值诚意金
        Route::get('topupearnest', ['as' => 'topupearnest', 'uses' => 'MoneyController@getTopUpEarnest']);
        Route::post('topupearnest', ['as' => 'topupearnest', 'uses' => 'MoneyController@postTopUpEarnest']);
        // 支付诚意金
        Route::get('earnest/{id}', ['as' => 'earnest', 'uses' => 'MoneyController@getEarnest']);
        Route::post('earnest', ['as' => 'postEarnest', 'uses' => 'MoneyController@postEarnest']);
        // 支付担保金
        Route::get('doposit/{id}', ['as' => 'doposit', 'uses' => 'MoneyController@getDoposit']);
        Route::post('doposit', ['as' => 'postDoposit', 'uses' => 'MoneyController@postDoposit']);
        Route::get('paydoposit/{id}/{serialId}', ['as' => 'paydoposit', 'uses' => 'MoneyController@getPayDoposit']);

        // 保存支付记录,去支付
        Route::get('gopay', ['as' => 'gopay', 'uses' => 'MoneyController@getGoPay']);
        Route::get('return/{payment}', ['as' => 'return', 'uses' => 'MoneyController@getReturn']);
        Route::post('notify/{payment}', ['as' => 'notify', 'uses' => 'MoneyController@postNotify']);
    });

    // 用户订单中心
    Route::group(['prefix' => 'order'], function () {
        Route::get('/', ['as' => 'index', 'uses' => 'OrderController@getUserOrder']);
    });

    // 短信功能路由
    Route::group(['prefix' => 'sms', 'as' => 'sms.'], function() {
        Route::post('getcode', ['as' => 'getcode', 'uses' => 'SmsController@postGetCode']);
    });
});


/**
* 经销商用户中心路由组
*/
Route::group(['namespace' => 'User', 'prefix' => 'dealer', 'as' => 'dealer.', 'middleware' => 'lpss'], function () {
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
        Route::get('ajaxcarrmodel/list',['as'=>'ajaxcarmodellist','uses'=>'CarmodelController@getMessage']);
        Route::post('ajaxcarmodel/{type}',['as'=>'ajaxcarmodel','uses'=>'CarmodelController@ajaxcarmodel']);
        Route::get('custorfile/{id}',['as'=>'custorfile','uses'=>'CustormerController@getCustfile']);
        Route::get('work_time/{id}',['as'=>'worktime','uses'=>'CustormerController@getWorkTime']);
        Route::post('work_time',['as'=>'postworktime','uses'=>'CustormerController@postWorkTime']);
        Route::post('ajaxCustfile/{type}',['as'=>'ajaxcustfile','uses'=>'CustormerController@ajaxCustfile']);
        Route::get('surance/{id}',['as'=>'surance','uses'=>'CarmodelController@getSurance']);
        Route::post('addcarmodel/{id}',['as'=>'addcarmodel','uses'=>'CarmodelController@AddCarmodel']);
        Route::post('editcarmodel/{id}',['as'=>'editcars','uses'=>'CarmodelController@editCarMess']);
	    //报价步骤get
        Route::get('baojia/{type}/{id}/{step?}',['as'=>'baojia','uses'=>'DealerBaojiaController@getBaojiaInfo']);
        //报价步骤post
        Route::post('baojia/{type}/{id}/{step?}',['as'=>'postbaojia','uses'=>'DealerBaojiaController@postBaojia']);
        //报价异步获取
        Route::get('baojia/ajax-get-data/{type}/',['as'=>'baojia.ajax.get.data','uses'=>'DealerBaojiaController@ajaxGetData']);
        Route::post('baojia/ajaxsubmit/{type}',['as'=>'baojia.ajax.post','uses'=>'DealerBaojiaController@ajaxSubmit']);
        
        //报价列表
        Route::get('baojialist/{type}',['as'=>'baojialist','uses'=>'DealerBaojiaController@getBaojiaList']);

});

	
/**
 * 经销商代理路由组
 */
Route::group(['namespace' => 'Business', 'prefix' => 'dealer', 'middleware' => 'lpss'], function () {
    //客户支付完诚意金等待页面
    // 收到诚意金等待反馈
    Route::get('feedback/{order_num}', ['as' => 'feedback', 'uses' => 'IndexController@feedBack']);
    // 代理修改了车辆信息
    Route::get('editcar/{order_num}', ['as' => 'editcar', 'uses' => 'IndexController@editCar']);
    // 代理没有修改信息，客户接受了特需文件反馈
    Route::get('acceptfile/{order_num}', ['as' => 'acceptfile', 'uses' => 'IndexController@acceptFile']);
    // 代理没有修改信息，客户不接受特需文件反馈
    Route::get('notacceptfile/{order_num}', ['as' => 'notacceptfile', 'uses' => 'IndexController@notAcceptFile']);
    // 代理有修改信息，客户不接受特需文件反馈
    Route::get('acceptedit/{order_num}', ['as' => 'acceptedit', 'uses' => 'IndexController@acceptEdit']);
    // 代理有修改信息，客户接受特需文件反馈，不接受修改
    Route::get('notacceptedit/{order_num}', ['as' => 'notacceptedit', 'uses' => 'IndexController@notAcceptEdit']);
    // 客户接受了特需文件和修改信息
    Route::get('acceptall/{order_num}', ['as' => 'acceptall', 'uses' => 'IndexController@acceptAll']);
    // 代理终止订单
    Route::get('stop1/{order_num}', ['as' => 'pdistop1', 'uses' => 'IndexController@stop1']);
    // 代理在发出交车前终止订单
    Route::get('stop2/{order_num}', ['as' => 'stop2', 'uses' => 'PdiController@stop2']);
    // 代理提议修改订单
    Route::get('pdiedit/{order_num}', ['as' => 'pdiedit', 'uses' => 'PdiController@pdiEdit']);
    // 确认反馈信息
    Route::post('save_feedback', ['as' => 'save_feedback', 'uses' => 'IndexController@saveFeedBack']);
    // 反馈ok
    Route::get('feedbackok/{order_num}', ['as' => 'feedbackok', 'uses' => 'IndexController@feedBackOk']);
    // 收到担保金等待响应
    Route::get('feedbackresponse/{order_num}', ['as' => 'feedbackresponse', 'uses' => 'IndexController@feedBackResponse']);
    // 保存响应
    Route::post('saveresponse', ['as' => 'saveresponse', 'uses' => 'IndexController@saveResponse']);
    //收到担保金等待响应ok
    Route::get('responseok/{order_num}', ['as' => 'responseok', 'uses' => 'IndexController@responseOk']);

    //响应客户的选装件修改
    Route::any('savexzj', ['as' => 'savexzj', 'uses' => 'IndexController@saveXzj']);

    // 客户接受订单修改，等待发出交车通知
    Route::get('waitnotice/{order_num}', ['as' => 'waitnotice', 'uses' => 'PdiController@waitNotice']);
    // 客户不接受订单修改，终止订单
    Route::get('stop3/{order_num}', ['as' => 'stop3', 'uses' => 'PdiController@stop3']);
    // 等待发出交车通知
    Route::get('pdiwait/{order_num}', ['as' => 'pdiwait', 'uses' => 'PdiController@index']);
    // 发出交车通知
    Route::get('pdinotice/{order_num}', ['as' => 'pdinotice', 'uses' => 'PdiController@PdiNotice']);
    // 保存代理填的信息
    Route::post('pdisave', ['as' => 'pdisave', 'uses' => 'PdiController@postPdiNotice']);
    // 已发交车通知
    Route::get('pdinoticeok/{order_num}', ['as' => 'pdinoticeok', 'uses' => 'PdiController@PdiNoticeOk']);
    // 预约交车反馈不ok，确认客户提交的信息
    Route::get('pdiconfirm/{order_num}', ['as' => 'pdiconfirm', 'uses' => 'PdiController@PdiConfirm']);
    // 预约交车反馈不ok，等待客户再确认
    Route::get('pdiwaitconfirm/{order_num}', ['as' => 'pdiwaitconfirm', 'uses' => 'PdiController@PdiWaitConfirm']);
    // 预约交车反馈不ok，客户已再次确认，提交补充信息
    Route::get('pdiok/{order_num}', ['as' => 'pdiok', 'uses' => 'PdiController@PdiOk']);
    // 保存补充信息
    Route::post('postpdiok', ['as' => 'postpdiok', 'uses' => 'PdiController@postPdiOk']);
    // 保存确认客户提交的信息
    Route::post('pdiconfirm', ['as' => 'pdiconfirm', 'uses' => 'PdiController@postPdiConfirm']);

    // 预约交车反馈ok
    Route::get('pdiconfirmok/{order_num}', ['as' => 'pdiconfirmok', 'uses' => 'PdiController@PdiConfirmok']);
    // 保存代理提交的补充信息
    Route::post('saveconfirm', ['as' => 'pdisaveconfirm', 'uses' => 'PdiController@postSaveConfirm']);
    // 预约交车完成
    Route::get('pdiend/{order_num}', ['as' => 'pdiend', 'uses' => 'PdiController@PdiEnd']);
    // 保存服务专员并跳转到提车
    Route::post('savezhuanyuan', ['as' => 'dealer.savezhuanyuan', 'uses' => 'PdiController@saveZhuanyuan']);
    // 填写提车信息
    Route::get('ticheinfo/{order_num}', ['as' => 'dealer.ticheinfo', 'uses' => 'PdiController@ticheInfo']);
    // 保存填写的提车信息
    Route::post('ticheinfo', ['as' => 'dealer.saveticheinfo', 'uses' => 'PdiController@saveTicheInfo']);
    // 提车完成经销商上牌
    Route::get('ticheend/{order_num}', ['as' => 'dealer.ticheend', 'uses' => 'PdiController@ticheEnd']);
    // 提车完成客户上牌
    Route::get('ticheenduser/{order_num}', ['as' => 'dealer.ticheenduser', 'uses' => 'PdiController@ticheEndUser']);
    
    //代理同意结算
    Route::post('agreecalc', ['as' => 'agreecalc', 'uses' => 'PdiController@agreeCalc']);
    
    // 保存客户超时，由售方反向添加上牌地区，上牌号码，车辆用途，补贴发放，车主名称信息
    Route::post('pdisavecarattrinfo', ['as' => 'dealer.pdisavecarattrinfo', 'uses' => 'PdiController@pdiSaveCarAttrInfo']);
    // 提交争议
    Route::get('zhengyi/{order_num}', ['as' => 'dealer.zhengyi', 'uses' => 'PdiController@zhengYi']);
    // 保存提交的争议
    Route::post('savezhengyi', ['as' => 'dealer.savezhengyi', 'uses' => 'PdiController@saveZhengYi']);
    // 调查调解
    Route::any('dispute/{order_num}', ['as' => 'dealer.dispute', 'uses' => 'PdiController@Dispute']);
    // 调解成功
    Route::get('mediateok/{order_num}', ['as' => 'dealer.mediateok', 'uses' => 'PdiController@MediateOk']);
    // 调解失败
    Route::get('mediatefail/{order_num}', ['as' => 'dealer.mediatefail', 'uses' => 'PdiController@MediateFail']);
    // 客户提交了争议，经销商申辩
    Route::any('defend/{order_num}', ['as' => 'dealer.defend', 'uses' => 'PdiController@Defend']);
    // 提车完成经销商 结算确认页面
    Route::get('jchjcheckmoney/{order_num}', ['as' => 'dealer.jchjcheckmoney', 'uses' => 'PdiController@jchjCheckMoney']);
    // 提车完成 经销商同意结算跳转页面
    Route::get('jchjhandleprocedures/{order_num}', ['as' => 'dealer.jchjhandleprocedures', 'uses' => 'PdiController@jchjHandleProcedures']);
    // 提车完成  结算完毕
    Route::get('jchjend/{order_num}', ['as' => 'dealer.jchjend', 'uses' => 'PdiController@jchjEnd']);
    
    // 代理ajax处理订单
    Route::post('ajax/{order_num}/{type}', ['as' => 'dealer.ajaxaction', 'uses' => 'PdiController@ajaxAction']);
    
    //代理查看订单概况
    Route::get('overview/{order_num}', ['as' => 'overview', 'uses' => 'IndexController@getOverview']);

});

/**
 * 管理后台路由组
 */
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => 'lpss'], function () {
    //后台查看订单详细页
    Route::get('showorder/{order_num}', ['as' => 'showorder', 'uses' => 'ShowOrderController@showOrder']);
});

Route::get('test','TestController@index');
