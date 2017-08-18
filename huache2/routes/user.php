<?php
/**
 * 用户中心路由组
 */
Route::group(['prefix'=>'member','namespace' => 'Member'], function () {
    //判断用户名是否存在（手机号）
    Route::get('checkUser' ,'PublicController@getCheckUser')->name('member.checkUser');
    //发送验证码
    Route::match(['get', 'post'] , 'sendSms' ,'PublicController@sendSms')
        ->name('member.sendSms')
        ->middleware('throttle:5');
    //验证手机短信
    Route::match(['get', 'post'] , 'checkSms' , 'PublicController@checkSms')->name('member.checkSms');

    //Route::get('redis' , 'AuthController@getRedis');
    //用户登录页面
    Route::get('login' , 'LoginController@showLoginForm')->name('login');
    //用户登录
    Route::post('checkLogin' ,'LoginController@login')->name('member.checkLogin');
    Route::get('login/freeze' , 'LoginController@getLoginFreeze')->name('login.freeze');

    Route::get('logout' ,'LoginController@logout')->name('user.logout');
    // Registration Routes...
    Route::get('register' ,'AuthController@showRegistrationForm')->name('user.getReg');
    //Route::get('register','AuthController@getRegisterForm');//(测试)
    Route::post('register' ,'AuthController@register')->name('user.postReg');
    #####---------------- 密码 -----------------------------------------
    //找回密码//修改密码
    Route::match(['get', 'post'] , 'pwdReset' ,'PasswordResetController@getPasswrod')->name('pwd.showResetForm');

    Route::get('showPassword' , 'PasswordResetController@showPassword')->name('pwd.showPassword');
    Route::get('sendSuccess' , 'PasswordResetController@sendSuccess')->name('pwd.pwdSendSms');//短信发送成功
    Route::post('verify/code' , 'PasswordResetController@getResetCode')->name('pwd.verifyCode');//验证密码

    Route::get('password/reset' , 'PasswordResetController@getResetForm')->name('pwd.getReset');
    Route::post('password/reset' , 'MobilePwdResetController@reset')->name('pwd.postRest');
    Route::get('mobile/answer' , 'PasswordResetController@showMoblieAnswerForm')->name('pwd.mobileAnswer');
    Route::get('mail/answer' , 'PasswordResetController@showEmailAnswerForm')->name('pwd.emailAnswer');
    Route::post('password/verify/answer','PasswordResetController@verifyMailAnswer')->name('answer.verify');
    Route::post('password/mobile/answer','PasswordResetController@verifyMobileAnswer')->name('answer.mobile_verify');

    Route::get('mail/show' ,'SendEmailController@showMailForm')->name('mail.showMail');
    //Route::get('mail/form' ,'SendEmailController@showLinkRequestForm')->name('mail.formMail');//测试发送
    Route::post('mail/send' ,'SendEmailController@sendResetLinkEmail')->name('mail.mailForm');
    Route::Post('mail/send_ajax','SendEmailController@ajaxSendEmail')->name('mail.sendAjax');
    Route::get('mail/null','SendEmailController@isEmailNull')->name('mail.isNull');
    Route::get('mail/check' , 'SendEmailController@checkMail')->name('mail.mailSuccess');
    Route::get('mail/reset' , 'SendEmailController@showLinkRequestForm')->name('mail.request');//显示密码重置页面
    Route::get('mail/sub','SendEmailController@mailResetForm')->name('mail.newReset');//设置新密码
    Route::post('mail/reset' , 'PasswordResetController@reset')->name('mail.reset');
    Route::get('mail/reset/{token}' , 'PasswordResetController@showResetForm')->name('mail.emailReset');//邮箱回调验证
    Route::get('pwdOver' , 'PasswordResetController@passwordOver')->name('pwd.pwdOver');//找回密码本轮结束
    Route::get('password/success' , 'PasswordResetController@getPasswrodSuccess')->name('pwd.pwdSuccess');//密码重置成功
    Route::get('password/time_out','PasswordResetController@timeOutFreeze')->name('time_out.freeze');
    ####------------------ end 密码 ---------------------------------------
    //首页
    Route::get('/' , 'IndexController@getIndex')->name('user.home');
    Route::get('info' , 'IndexController@getInfo')->name('user.info');
    ###----------------------- 用户资料 ---------------------------------
    Route::match(['get','post'],'account/{type}','IndexController@upInfo')
        ->where(['type'=>'photo|call|address'])
        ->name('account.upInfo');//修改头像
    Route::get('check/isCart','IndexController@checkIdCart')->name('user.isIdCart');
    ###---------------------- 账号安全管理 -------------------------------
    Route::get('account','AccountController@getIndex')->name('user.safe');
    Route::get('mobile/verify','AccountController@getMobile')->name('mobile.verify');//修改密码手机验证
    Route::get('email/verify','AccountController@getEmail')->name('email.verify');//修改密码手机验证
    Route::match(['get','post'],'account/reset','AccountController@pwdReset')->name('account.reset');//修改密码
    Route::match(['get','post'],'email/send','AccountController@sendEmail')->name('account.sendMail');//发送验证邮箱
    Route::match(['get','post'],'email/check','AccountController@checkEmail')->name('account.checkEmail');
    Route::get('account/pwdEnd','AccountController@pwdEnd')->name('account.pwdEnd');
    //添加及修改邮箱
    Route::match(['get','post'],'email/add','AccountController@emailAdd')->name('email.add');//添加邮箱验证手机
    Route::get('verify/mobile/email','AccountController@verifySuccessAddEmail')->name('email.success-addEmail');//手机验证成功-到添加邮箱
    Route::get('email/send/check','AccountController@SendMailOk')->name('account.vefifyEmail');//邮件发送成功验证邮箱
    Route::post('email/save','AccountController@saveEmail')->name('email.save');//保存邮箱
    Route::get('email/add/success','AccountController@addEmailSuccess')->name('account.mailSuccess');//邮件新增成功
    //修改邮箱
    Route::match(['get','post'],'upemail/seep1','AccountController@upEmailSeep1')->name('upemail.seep1');//修改邮箱验证手机
    Route::match(['get','post'],'upemail/seep2','AccountController@upEmailSeep2')->name('upemail.seep2');//手机验证成功-发送验证码页面
    Route::match(['get','post'],'upemail/seep3','AccountController@upEmailSeep3')->name('upemail.seep3');
    Route::match(['get','post'],'upemail/seep4','AccountController@upEmailSeep4')->name('upemail.seep4');
    Route::match(['get','post'],'upemail/seep5','AccountController@upEmailSeep5')->name('upemail.seep5');
    Route::match(['get','post'],'upemail/success','AccountController@upEmailSeep6')->name('upemail.success');
    //修改手机
    Route::match(['get','post'],'upmobile/seep1','AccountController@upMobileSeep1')->name('upmobile.seep1');
    Route::match(['get','post'],'upmobile/seep2','AccountController@upMobileSeep2')->name('upmobile.seep2');
    Route::match(['get','post'],'upmobile/seep3','AccountController@upMobileSeep3')->name('upmobile.seep3');
    Route::match(['get','post'],'upmobile/seep4','AccountController@upMobileSeep4')->name('upmobile.seep4');
    Route::match(['get','post'],'upmobile/seep5','AccountController@upMobileSeep5')->name('upmobile.seep5');
    Route::match(['get','post'],'upmobile/seep6','AccountController@upMobileSeep6')->name('upmobile.seep6');
    ###---------------------- end 账号安全 -------------------------------

    ####-------------------- 用户中心文件管理 ------------------------------
    Route::get('auth/addCart' , 'FileManageController@AddShowIdCartVerify')->name('auth.addShowIdCart');
    Route::get('auth/idCart' , 'FileManageController@showIdCartVerify')->name('auth.showIdCart');
    Route::post('auth/idCart' , 'FileManageController@saveIdCart')->name('auth.postIdCart');
    //银行卡管理
    Route::get('bank' , 'BankController@getIndex')->name('user.bank');
    Route::get('bank/update/{id}' , 'BankController@getEdit')
        ->where(['id'=>'[0-9]+'])
        ->name('bank.update');
    Route::post('bank' , 'BankController@postSave')->name('bank.save');
    Route::get('bank/show/{id}','BankController@getShow')
        ->where(['id'=>'[0-9]+'])
        ->name('bank.addShow');
    Route::get('bank/showup/{id}','BankController@getUpdateShow')
        ->where(['id'=>'[0-9]+'])
        ->name('bank.updateShow');

    Route::get('bank/list','BankController@getBankList')->name('my.brankList');
    ###--------------------- end 用户中心文件管理 ----------------------------------

    ###--------------------- 特殊文件管理 ------------------------------------------
    Route::get('special/list', 'AreaSpecialFileController@showList')->name('special.list');
    Route::get('special/add', 'AreaSpecialFileController@showAdd')->name('special.add');
    Route::get('special/view/{id}', 'AreaSpecialFileController@showView')
        ->where(['id'=>'[0-9]+'])
        ->name('special.view');
    //Route::get('special/pass', 'AreaSpecialFileController@showPass')->name('special.pass');
    //Route::get('special/rejected', 'AreaSpecialFileController@Rejected')->name('special.ejected');
    Route::get('special/download','AreaSpecialFileController@showDownloadList')->name('special.download');
    Route::post('special/save','AreaSpecialFileController@postSave')->name('special.save');
    //Route::controller('special', 'AreaSpecialFileController');
    ###---------------------- end 特殊文件管理 -------------------------------------
    //成功跳转
    Route::get('success' , 'PublicController@getSuccess')->name('reset.success');

    ###--------------------- 用户财务 ----------------------------------------
    Route::get('balance','FinancialController@myBalance')->name('my.myBalance');//我的余额
    Route::get('hwache_pay','FinancialController@myPay')->name('my.hwachePay');//加信宝
    Route::get('recorded_at','RecordedAtController@getIndex')->name('my.RecordedAt');//我的入账
    Route::get('recorded_at/{id}','RecordedAtController@showDetail')
        ->where(['id'=>'[0-9]+'])
        ->name('RecordedAt.detail');//我的入账详情

    Route::get('withdrawal','WithdrawalController@getIndex')->name('my.Withdrawal');//我的提款记录
    Route::get('withdrawal/application','WithdrawalController@showApplication')->name('Withdrawal.Application');//提款申请;
    Route::post('withdrawal/application','WithdrawalController@showApplication')->name('Withdrawal.Application')->middleware('withdraw.filter:member');//提款申请;
    Route::any('withdrawal/bank/{id}','WithdrawalController@showBankEdit')
        ->where(['id'=>'[0-9]+'])
        ->name('Withdrawal.Bank');//完善银行卡信息
    Route::get('withdrawal/ceiling','WithdrawalController@showCeiling')->name('Withdrawal.Ceiling');//我的提现额度管理
    Route::get('withdrawal/line','WithdrawalController@showLine')->name('Withdrawal.Line');//提款线路
    Route::get('withdrawal/{id}','WithdrawalController@showDetail')
        ->where(['id'=>'[0-9]+'])
        ->name('Withdrawal.Detail');//提款线路详情

    Route::get('welfare','WelfareController@getIndex')->name('my.Welfare');//我的福利
    Route::post('vouchers','WelfareController@getVouchersList')->name('my.Welfare.vouchers');//我的福利

    Route::get('receipt','ReceiptController@getIndex')->name('my.Receipt');//我的发票
    #------------------------ end 用户财务 -----------------------------------

    ###--------------------- 用户订单 ---------------------------------------
    Route::get('order','OrderController@getIndex')->name('my.order');//订单列表
    Route::get('order/{order_sn}','OrderController@showDetail')->name('myOrder.Detail');//订单详情
    ###---------------------- end 用户订单 ----------------------------------

    ###---------------------- 购车支付 --------------------------------------------------
    Route::get('pay/earnest/{id}',['as'=>'pay.earnest','uses'=>'PayController@payEarnest']);//支付诚意金页面
    Route::post('payearnest/balance',['as'=>'payearnest.balance','uses'=>'PayController@payEarnestByBalance']);//余额支付诚意金
    Route::post('payearnest/charge',['as'=>'payearnest.charge','uses'=>'PayController@payEarnestByCharge']);//线上充值支付诚意金
    Route::any('pay/deposit/{id}',['as'=>'pay.deposit','uses'=>'PayController@payDeposit']);//支付担保金页面
    Route::any('pay/online',['as'=>'pay.online','uses'=>'PayController@rechargeOnline']);//线上充值
    Route::any('paydeposit/online/{id}',['as'=>'paydeposit.online','uses'=>'PayController@payDepositOnline']);//线上充值支付担保金
    Route::get('paydeposit/offline/{id}',['as'=>'paydeposit.offline','uses'=>'PayController@payDepositOffline']);//银行转账支付担保金页面
    Route::any('paydeposit/receipt/{id}',['as'=>'paydeposit.receipt','uses'=>'PayController@payDepositReceipt']);//银行转账支付担保金凭证


    ###---------------------- end 购车支付 ----------------------------------------------

    ###----------------------- 充值 -----------------------------------------------------
    Route::get('recharge/result','PayController@payResult')->name('recharge.result');
    Route::get('recharge/{receipt?}','PayController@payRecharge')->name('pay.recharge');
    Route::post('recharge/receipt','PayController@postReceipt')->name('recharge.receipt');
    ### --------------------- end 充值 --------------------------------------------------
    if (env('APP_ENV') == 'product') {
        ###-------------------------------------------------上线后台临时路由------------------------------------------------
        Route::get('balance', ['as' => 'my.myBalance', function () {
            return view('Temp.financial_temp', ['active' => 'yue', 'title' => '我的余额', 'nav' => 'myBalance']);
        }]);
        Route::get('recorded_at', ['as' => 'my.RecordedAt', function () {
            return view('Temp.financial_temp', ['active' => 'ruzhang', 'title' => '我的转入', 'nav' => 'RecordedAt']);
        }]);
        Route::get('withdrawal', ['as' => 'my.Withdrawal', function () {
            return view('Temp.financial_temp', ['active' => 'tixian', 'title' => '我的提现', 'nav' => 'Withdrawal']);
        }]);
        Route::get('welfare', ['as' => 'my.Welfare', function () {
            return view('Temp.financial_temp', ['active' => 'fuli', 'title' => '我的福利', 'nav' => 'Welfare']);
        }]);
        Route::get('special/download',['as' => 'special.download',  function() {
            return view('Temp.financial_temp',['active' => 'download', 'title' => "上牌特殊文件-下载", 'nav' => 'myDownload']);
}]);
        Route::get('pay/online',['as'=>'pay.online', function(){
            return view('Temp.financial_temp',['active' => 'add_price', 'title' => "充值", 'nav' => 'price']);
        }]);
        ###-----------------------------------------------------------------------------------------------------------------
    }
});

//Auth::routes();

