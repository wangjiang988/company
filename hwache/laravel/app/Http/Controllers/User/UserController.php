<?php
/**
 * 会员中心控制器
 *
 * 会员资料，订单管理，财务中心，实名认证等等
 *
 * @package   User
 * @author    李扬(Andy) <php360@qq.com>
 * @link      技安后院 http://www.moqifei.com
 * @copyright 苏州华车网路科技有限公司 http://www.hwache.com
 */
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Input;
use Validator;
use DB;
use App\Http\Requests;
use Request;
use Session;
use Config;
use App\Models\HgUser;
use App\Models\Area;
use App\Com\Hwache\User\User;
use App\Models\HgCart;
use App\Models\HgInvoice;
use App\Models\HgTellUs;
use App\Com\Hwache\Order\Order;
use App\Models\HgCarAtt;
use App\Models\HgCartAttr;
use App\Models\HgInvoiceConfig;
use App\Core\Contracts\Money\Money;
use Illuminate\Secode\Seccode;
class UserController extends Controller
{
    private $user;
    private $order;
    private $file;
    private $money;

    public function __construct(User $user, Order $order, Money $money)
    {
        $this->middleware('user.status');

        $this->user = $user;
        $this->order = $order;
        $this->money = $money;
    }

    /**
     * 会员中心首页 默认调用 memberOrderOp
     */
    public function getIndex()
    {
        // 检测来路URL
        if ($redirectUrl = session('redirectUrl')) {
            session()->forget('redirectUrl');
            return redirect($redirectUrl);
        }

        return redirect()->route('user.member_info');
    }

    /**
     * 用户中心 首页用户个人资料
     */
    public function memberInfoOp()
    {
        $act = Request::Input('act');
        if ($act == 'submit_other_info') {
            $photo = Request::file('file');//上传头像
            if (!empty($photo) && $photo->isValid()) {
                $entension = $photo->getClientOriginalExtension();
                if (!allowext($entension)) dd('文件类型不允许');
                $fileName = session('user.member_id') . '_' . date('YmdHms') . mt_rand(1000, 9999) . '.' . $entension;
                $filePath = 'avatar/' . date("Y") . '/' . date("m") . '/';
                $photo->move(config('app.uploaddir') . $filePath, $fileName);
                $data['member_avatar'] = $filePath . $fileName;
            }
            $orgin_avatar = Request::Input('orgin_avatar');
            if (!empty($orgin_avatar)) {//needtodobyjerry modify path
                // TODO 后续修改
                @unlink(config('app.uploaddir') . $orgin_avatar);
            }
            //echo config('app.uploaddir').$orgin_avatar;
            $data['member_title'] = Request::Input('title') == 'other' ? Request::Input('other_title') : Request::Input('title');
            $data['member_areainfo'] = Request::Input('province') . '-' . Request::Input('city');
            $data['member_address'] = Request::Input('address');
            HgUser::where(array('member_id' => session('user.member_id')))->update($data);

        }
        $view = ['title' => '会员中心-用户个人资料'];
        $view['flag'] = 'memberInfo';

        $memberInfo = HgUser::getMember(session('user.member_id'), 'Y')->toArray();
        $view['memberInfo'] = $memberInfo;
        $topArea = Area::getTopArea()->toArray();
        $view['topArea'] = $topArea;
        //print_r($topArea);
        return view('usercenter.member_info', $view);

    }

    /**
     * 用户中心 安全设置
     */
    public function memberSafeOp($do = 'index')
    {
        $view['flag'] = 'memberSafe';
        $memberInfo = HgUser::getMember(session('user.member_id'), 'Y')->toArray();
        $view['memberInfo'] = $memberInfo;
        $act_form = Request::Input('act_form');
        if ($do == 'index') {
            $view['title'] = '会员中心-安全设置';
            return view('usercenter.member_safe', $view);
        } elseif ($do == "passwd_email_check") {//修改密码时邮箱验证)
            if ($act_form == 'sub') {
                $email = $memberInfo['member_email'];
                if (!empty($email)) {
                    $this->user->sendEmail($email, 'send_email_to_change_passwd');
                }
                return view('usercenter.passwd_email_send', $view);
            }

            $view['title'] = '会员中心-安全设置-修改密码-邮箱验证';
            return view('usercenter.passwd_email_check', $view);
        } elseif ($do == "passwd_phone_check") {//修改密码时手机验证

            if ($act_form == 'sub') {
                return redirect('/user/memberSafe/modify_password');
            }
            $view['title'] = '会员中心-安全设置-修改密码-手机验证';
            return view('usercenter.passwd_phone_check', $view);
        } elseif ($do == "modify_password") {//修改密码页面
            if (session('verify_status') == 's1' || session('change_phone_status') == 1) {
                if ($act_form == 'sub') {
                    $data['member_passwd'] = bcrypt(Input::get('pwd'));
                    $effect = HgUser::where(array('member_id' => session('user.member_id')))->update($data);
                    if ($effect) {
                        return redirect('/user/memberSafe/modify_password_success');
                    } else {
                        die("密码更新失败");
                    }

                }
                $view['title'] = '会员中心-安全设置-修改密码';
                return view('usercenter.modify_password', $view);
            } else {
                die('非法操作，请先使用手机或者邮箱 触发的方式去修改密码');
            }

        } elseif ($do == "modify_password_success") {//修改密码成功页面
            $view['title'] = '会员中心-安全设置-修改密码成功';
            return view('usercenter.modify_password_success', $view);
        } elseif ($do == "check_real_member") {//实名认证

            if ($act_form == 'sub') {
                $dataMemberInfo['member_truename'] = Request::Input('username');
                $dataVerifyInfo['card_num'] = Request::Input('userNumcard');
                $dataVerifyInfo['bank_city'] = Request::Input('province') . '-' . Request::Input('city');
                $dataVerifyInfo['bank_addr'] = Request::Input('address');
                $dataVerifyInfo['bank_account'] = Request::Input('bank_account');

                //先删除原始的 再提交保存图片
                @unlink(config('app.uploaddir') . $memberInfo['card_photo1']);
                @unlink(config('app.uploaddir') . $memberInfo['card_photo2']);
                @unlink(config('app.uploaddir') . $memberInfo['bank_photo1']);
                @unlink(config('app.uploaddir') . $memberInfo['bank_photo2']);

                $photo['card_photo1'] = Request::File('card_photo1');
                $photo['card_photo2'] = Request::File('card_photo2');
                $photo['bank_photo1'] = Request::File('bank_photo1');
                $photo['bank_photo2'] = Request::File('bank_photo2');

                $filePath = 'member_photo/' . date("Y") . '/' . date("m") . '/';
                foreach ($photo as $k => $p) {
                    if (!empty($p) && $p->isValid()) {
                        $entension = $p->getClientOriginalExtension();
                        if (!allowext($entension)) dd('文件类型不允许');
                        $fileName = session('user.member_id') . '_' . date('YmdHms') . mt_rand(1000, 9999) . '.' . $entension;
                        $p->move(config('app.uploaddir') . $filePath, $fileName);
                        $dataVerifyInfo[$k] = $filePath . $fileName;
                    }
                }
                $dataVerifyInfo['name_verify'] = 1;//0未实名认证 1提交实名认证  2  待审核

                $effectMember = HgUser::where(array('member_id' => session('user.member_id')))->update($dataMemberInfo);
                $effectVerify = DB::table("member_verify")->where(array('member_id' => session('user.member_id')))->update($dataVerifyInfo);
                if ($effectMember && $effectVerify) {
                    echo 'success';
                    return redirect('/user/memberSafe/check_real_member');exit;
                }

                //exit;
            }
            $view['title'] = '会员中心-安全设置-实名认证';
            $topArea = Area::getTopArea()->toArray();
            $view['topArea'] = $topArea;

            return view('usercenter.check_real_member', $view);
        } elseif ($do == "email_check") {//邮箱验证
        	
            $email = trim(Request::Input('email'));
            if (!empty($email)) {
                $this->user->sendEmail($email, 'member_verify_email');
                $dataVerifyInfo['email_verify'] = 1;//1为已结申请验证 但是未还未进入邮箱 验证
                DB::table("member_verify")->where(array('member_id' => session('user.member_id')))->update($dataVerifyInfo);
                $data['member_email'] = $email;
                $effect = HgUser::where(array('member_id' => session('user.member_id')))->update($data);
                return redirect('/user/memberSafe/email_check_send');
            }
            $view['title'] = '会员中心-安全设置-邮箱验证';
            return view('usercenter.email_check', $view);
        } elseif ($do == "email_check_send") {//邮箱验证 已经发送验证邮箱
            $view['title'] = '会员中心-安全设置-邮箱验证发送验证短信';
            return view('usercenter.email_check_send', $view);
        } elseif ($do == "email_check_success") {//邮箱验证 邮箱验证OK
            $view['title'] = '会员中心-安全设置-邮箱验证成功';
            return view('usercenter.email_check_success', $view);
        } elseif ($do == "email_check_failure") {//邮箱验证 邮箱验证失败
            $view['title'] = '会员中心-安全设置-邮箱验证失败';
            return view('usercenter.email_check_failure', $view);
        } elseif ($do == "phone_change") {//修改密码时手机验证 已经发送短信
            if (Request::Input('act_form') == 'sub' ) {
            	if(session('change_phone_status') == 1){
                	return redirect('/user/memberSafe/phone_change_input');
                	exit;
            	}
            }
	    	
            $view['title'] = '会员中心-安全设置-更换手机号码';

            return view('usercenter.phone_change', $view);
        } elseif ($do == "phone_change_input") {//修改密码输入新的手机号码

            if (Request::Input('act_form') == 'sub' && session('change_phone_status') == '2') {
                $mobile = Request::Input('phone');
                $effect = HgUser::where(array('member_id' => session('user.member_id')))->update(array('member_mobile' => $mobile));
                if ($effect) {
                    Session::forget('change_phone_status');
                    return redirect('/user/memberSafe/phone_change_success');
                } else {
                    die('更新失败，请重新填写');
                }
                exit;
            }
            $view['title'] = '会员中心-安全设置-输入新的手机号码';
            return view('usercenter.phone_change_input', $view);
        } elseif ($do == "phone_change_success") {//修改密码时手机验证 手机号码更换成功
            $view['title'] = '会员中心-安全设置-手机号码更换成功';
            return view('usercenter.phone_change_success', $view);
        } elseif ($do == "phone_change_by_email") {//修改密码时手机验证 手机号码更换成功
            if (Request::Input('act_form') == 'sub') {
                $email = $memberInfo['member_email'];
                if (!empty($email)) {
                    $this->user->sendEmail($email, 'change_phone_by_email');
                }
                return view('usercenter.phone_change_by_email_send', $view);
            }
            $view['title'] = '会员中心-安全设置-通过邮箱修改手机号码';
            return view('usercenter.phone_change_by_email', $view);
        }
    }

    /**
     * 用户中心 银行账户设置
     */
    public function memberBankAccountOp()
    {
        $act_form = Request::Input('act_form');
        if ($act_form == "sub") {//默认页面
            $dataMemberInfo['member_truename'] = Request::Input('username');
            $dataAccountInfo['bank_city'] = Request::Input('province') . '-' . Request::Input('city');
            $dataAccountInfo['bank_addr'] = Request::Input('address');
            $dataAccountInfo['bank_account'] = Request::Input('bank_account');

            $memberInfo = HgUser::getMember(session('user.member_id'), 'Y')->toArray();
            //先删除原始的 再提交保存图片
            //@unlink(config('app.uploaddir') . $memberInfo['bank_photo1']);
            //@unlink(config('app.uploaddir') . $memberInfo['bank_photo2']);

            $photo['bank_photo1'] = Request::File('bank_photo1');
            $photo['bank_photo2'] = Request::File('bank_photo2');

            $filePath = 'member_photo/' . date("Y") . '/' . date("m") . '/';

            foreach ($photo as $k => $p) {
                if (!empty($p) && $p->isValid()) {
                    $entension = $p->getClientOriginalExtension();
                    if (!allowext($entension)) dd('文件类型不允许');
                    $fileName = session('user.member_id') . '_' . date('YmdHms') . mt_rand(1000, 9999) . '.' . $entension;
                    $p->move(config('app.uploaddir') . $filePath, $fileName);
                    $dataAccountInfo[$k] = $filePath . $fileName;
				    if(!empty($memberInfo[$k])){
		    		    	//先删除原始的 再提交保存图片
		    		    	@unlink(config('app.uploaddir').$memberInfo[$k]);
		    		 }
                }
            }
            $effectMember = HgUser::where(array('member_id' => session('user.member_id')))->update($dataMemberInfo);
            $effectVerify = DB::table("member_verify")->where(array('member_id' => session('user.member_id')))->update($dataAccountInfo);
            if ($effectMember && $effectVerify) {
                echo 'success';
            }
            //exit;

        }
        $view = ['title' => '会员中心-银行账户设置'];
        $view['flag'] = 'memberBankAccount';
        $memberInfo = HgUser::getMember(session('user.member_id'), 'Y')->toArray();
        $view['memberInfo'] = $memberInfo;
        $topArea = Area::getTopArea()->toArray();
        $view['topArea'] = $topArea;

        return view('usercenter.member_bank_account', $view);


    }

    /**
     * 用户中心 我的财务
     */
    public function memberFinanceOp()
    {
        $view = ['title' => '会员中心-我的财务'];
        $view['flag'] = 'memberFinance';
        return view('usercenter.my_finance', $view);
    }

    /**
     * 用户中心 我的财务 提现
     */
    public function memberCashOp()
    {
        $view = ['title' => '会员中心-我的财务-提现'];
        $view['flag'] = 'memberCash';
        return view('usercenter.cash', $view);
    }

    /**
     * 用户中心 我的财务发票  列表
     */
    public function memberInvoiceListOp()
    {
        $view = ['title' => '会员中心-我的财务发票'];
        $view['flag'] = 'memberInvoiceList';
        $invoiceList = HgInvoice::getAllInvoiceByUser(session('user.member_id'));
        if (count($invoiceList) > 0) {
            $invoiceList->toArray();
        } else {
            $invoiceList = array();
        }
        $invoiceStatus = config('invoice');
        $view['invoiceStatus'] = $invoiceStatus;
        $view['invoiceList'] = $invoiceList;
        return view('usercenter.invoice_list', $view);
    }

    /**
     * 用户中心 我的财务发票
     */
    public function memberInvoiceOp($order_num)
    {

    	$view = ['title' => '会员中心-我的财务发票'];
        $view['flag'] = 'memberInvoice';
        $view['order_num'] = $order_num;
        $view['order_money'] = '2000';//测试数据 默认消费金额为2000  需要清理
        if (!empty($order_num)) {
            $order = $this->order->getOrder($order_num, session('user.member_id'), true);
            if (empty($order)) {
                die("此订单不存在");
            }
            //订单进入结算状态才能进行开票
            if ($order->calc_status != 1) {
                die("此订单尚未进入结算阶段，所以无法开票");
            }
            $order = $order->toArray();
            $view['order'] = $order;
            $invoice = HgInvoice::getInvoiceByOrder($order_num);
			$member_id = session('user.member_id');
			$memInfo = HgUser::getMember($member_id)->toArray();
			$member_truename = $memInfo['member_truename'];
			
			
			
			$cartAttr = HgCartAttr::GetOrderAttr(['cart_id'=>$order['id']]);
			$carUserInfo = !empty($cartAttr['user_carinfo'])?unserialize($cartAttr['user_carinfo']):array('yongtu'=>'');
			$carPdiInfo  = !empty($cartAttr['pdi_carinfo'])?unserialize($cartAttr['pdi_carinfo']):array('yongtu'=>'');
            //$view['yongtu'] = !empty($carUserInfo['yongtu'])?$carUserInfo['yongtu']:$carPdiInfo['yongtu'];
            $view['yongtu'] = $order['buytype']==1?'非营业企业客车':'非营业个人客车';
            //$view['yongtu'] = '非营业企业客车';
            //$order['cart_sub_status'] = 1001;
            
            $inv_titleArray = array();//初始化发票抬头

            //订单走完
            if($order['calc_status'] ==1){
            	$inv_titleArray[] = $member_truename;
            	if($member_truename != $order['reg_name'] ){
            		$inv_titleArray[] = $order['reg_name'];
            	}
            	if( $view['yongtu']=='非营业企业客车' ){
            		$inv_titleArray[] = '其它';
            	}
            }else{//订单没有完成
            	$inv_titleArray[] = $member_truename;
            	if( $view['yongtu']=='非营业企业客车' ){
            		$inv_titleArray[] = '其它';
            	}
            }           
            
            $view['inv_titleArray'] = $inv_titleArray;
            if (count($invoice) > 0) {//发票存在  取值
            	if(Request::Input("redo")=='y' && $invoice->re_do_status ==0){
            		die('需要系统管理员同意后方能重开！');
            	}
                if (Request::Input("act_form") == 'sub_redo') {//重开发票
                    $data['return_deliver'] = Request::Input("kuaidi");
                    $data['return_deliver_num'] = Request::Input("kuaidisn");
                    $data['return_deliver_date'] = date("Y-m-d H:i:s");

                    $data['inv_money'] = $invoice->inv_money;
                    $data['inv_title'] = Request::Input("inv_title");
                    $data['inv_goto_addr'] = Request::Input("address");
                    $data['inv_rec_name'] = Request::Input("receiver");
                    $data['inv_rec_mobphone'] = Request::Input("phone");
                    $data['order_num'] = $order_num;
                    $data['inv_apply_date'] = date("Y-m-d H:i:s");
                    $data['member_id'] = session('user.member_id');
                    $data['invoice_status'] = '1';
                    $data['inv_state'] = Request::Input("invoice_type");//发票类型
                    $data['invoice_type'] = 1;//发票类型
                    $data['return_reason'] = Request::Input("reason");//普通发票
                    
                    if($data['inv_state'] == 2){
                    	$data['inv_code'] = Request::Input("addsn");
                    	$data['inv_reg_addr'] = Request::Input("addaddress");
                    	$data['inv_reg_phone'] = Request::Input("addphone");
                    	$data['inv_reg_bname'] = Request::Input("addbank");
                    	$data['inv_reg_baccount'] = Request::Input("addaccount");
                    }
                    
                    $eff = HgInvoice::insert($data);
                    $effOri = HgInvoice::where(array('inv_id' => $invoice->inv_id))->update(array('invoice_status' => 5, 'invoice_type' => 0));
                    if (!$eff || !$effOri) {
                        die('提交异常，请重新提交开票信息');
                    } else {
                        return redirect('/user/memberInvoice/' . $order_num);
                    }
                }elseif(Request::Input("act_form") == 'sub_invoice_re_edit'){//重新编辑后提交
                	$data['inv_title'] = Request::Input("inv_title");
                	$data['inv_goto_addr'] = Request::Input("address");
                	$data['inv_rec_name'] = Request::Input("receiver");
                	$data['inv_rec_mobphone'] = Request::Input("phone");
                	$data['order_num'] = $order_num;
                	$data['inv_apply_date'] = date("Y-m-d H:i:s");
                	
                	$data['inv_state'] = Request::Input("invoice_type");//普通发票
                	if($data['inv_state'] == 2){
                		$data['inv_code'] = Request::Input("addsn");
                		$data['inv_reg_addr'] = Request::Input("addaddress");
                		$data['inv_reg_phone'] = Request::Input("addphone");
                		$data['inv_reg_bname'] = Request::Input("addbank");
                		$data['inv_reg_baccount'] = Request::Input("addaccount");
                	}
                	$data['invoice_re_edit'] = 1;
                	$eff = HgInvoice::where(array('inv_id' => $invoice->inv_id))->update($data);
                	if (!$eff ) {
                		die('提交异常，请重新提交开票信息');
                	} else {
                		return redirect('/user/memberInvoice/' . $order_num);
                	}
                	
                }elseif(Request::Input("act_form") =='sub_redo_re_edit'){//重新申请 退回编辑后提交
                	$re_invoice = HgInvoice::getInvoiceByOrder($order_num, 'C')->toArray();
                	$data['return_deliver'] = Request::Input("kuaidi");
                	$data['return_deliver_num'] = Request::Input("kuaidisn");
                	$data['return_deliver_date'] = date("Y-m-d H:i:s");
                	
                	$data['inv_money'] = $re_invoice['inv_money'];
                	$data['inv_title'] = Request::Input("inv_title");
                	$data['inv_goto_addr'] = Request::Input("address");
                	$data['inv_rec_name'] = Request::Input("receiver");
                	$data['inv_rec_mobphone'] = Request::Input("phone");
                	
                	$data['inv_apply_date'] = date("Y-m-d H:i:s");
                	$data['inv_state'] = Request::Input("invoice_type");//发票类型
                	$data['return_reason'] = Request::Input("reason");//普通发票
                	
                	$data['invoice_re_edit'] = 1;
                	$eff = HgInvoice::where(array('inv_id' => $re_invoice['inv_id']))->update($data);
                	if (!$eff ) {
                		die('提交异常，请重新提交开票信息');
                	} else {
                		return redirect('/user/memberInvoice/' . $order_num);
                	}
                }

                $receive = Request::Input("receive");
                if ($receive == 'ok') {//用户确认收到发票 ajax方法获取
                    HgInvoice::where('order_num', '=', $order_num)
                        ->where('invoice_type', '=', 0)
                        ->update(array('invoice_status' => 4));
                    echo 'ok';
                    exit;
                }
                if ($receive == 'reok') {//用户确认收到发票 ajax方法获取
                    HgInvoice::where('order_num', '=', $order_num)
                        ->where('invoice_type', '=', 1)
                        ->update(array('invoice_status' => 4));
                    echo 'ok';
                    exit;
                }
                if ($invoice->invoice_status == 5) {//如果发票显示为重开，获取重开的发票信息
                    $re_invoice = HgInvoice::getInvoiceByOrder($order_num, 'C');
                    if (count($re_invoice) > 0) {
                        $re_invoice = $re_invoice->toArray();
                    } else {
                        $re_invoice = array();
                    }
                } else {
                    $re_invoice = array();
                }
                $view['re_invoice'] = $re_invoice;
                $invoice = $invoice->toArray();//存在发票 显示申请开票的信息
            } else {

                $invoice = array();//不存在发票，显示开具发票页面
               
                if (Request::Input("act_form") == 'sub') {//申请开票

                    $data['inv_money'] = $view['order_money'];
                    $data['inv_title'] = Request::Input("inv_title");
                    $data['inv_goto_addr'] = Request::Input("address");
                    $data['inv_rec_name'] = Request::Input("receiver");
                    $data['inv_rec_mobphone'] = Request::Input("phone");
                    $data['order_num'] = $order_num;
                    $data['inv_apply_date'] = date("Y-m-d H:i:s");
                    $data['member_id'] = session('user.member_id');
                    $data['invoice_status'] = '1';
                    $data['inv_state'] = Request::Input("invoice_type");//普通发票
                    if($data['inv_state'] == 2){
                    	$data['inv_code'] = Request::Input("addsn");
                    	$data['inv_reg_addr'] = Request::Input("addaddress");
                    	$data['inv_reg_phone'] = Request::Input("addphone");
                    	$data['inv_reg_bname'] = Request::Input("addbank");
                    	$data['inv_reg_baccount'] = Request::Input("addaccount");
                    }
                    
                    //预计开票时间计算
                    if($data['inv_state'] == 2){
                    	//计算预计开票日期
                    	$invoiceConfig=HgInvoiceConfig::first()->toArray();
                    	$existNum = $invoiceConfig['zyfp_num'];
                    	$toDoNum = HgInvoice::where(array('inv_state'=>2,'invoice_status'=>1))->count();
                    	$period = $invoiceConfig['zyfp_period'];
                    	$max = $invoiceConfig['zyfp_max'];
                    }else{
                    	//计算预计开票日期
                    	$invoiceConfig=HgInvoiceConfig::first()->toArray();
                    	$existNum = $invoiceConfig['ptfp_num'];
                    	$toDoNum = HgInvoice::where(array('inv_state'=>1,'invoice_status'=>1))->count();
                    	$period = $invoiceConfig['ptfp_period'];
                    	$max = $invoiceConfig['ptfp_max'];
                    }
                    
                    if($existNum >=$toDoNum){//库存大于 待处理发票
                    	$inv_esitmate_date = date("Y-m-d",time()+$period*86400);
                    }else{
                    	$days = (floor(($toDoNum-$existNum)/$max)+2)*$period;
                    	$inv_esitmate_date = date("Y-m-d",time()+($days*86400) );
                    }
                    $data['inv_estimate_date'] = $inv_esitmate_date;
                    $eff = HgInvoice::insert($data);
                    if (!$eff) {
                        die('提交异常，请重新提交开票信息');
                    } else {
                        return redirect('/user/memberInvoice/' . $order_num);
                    }
                }
            }
            $view['invoice'] = $invoice;

        }
       
        return view('usercenter.invoice', $view);
    }

    /**
     * 用户中心 我的文件
     */
    public function memberFileOp()
    {
        $view = ['title' => '会员中心-我的文件'];
        $view['flag'] = 'memberFile';
        $fileList = HgTellUs::getAllFileByUser(session('user.member_id'));
        if (count($fileList) > 0) {
            $fileList = $fileList->toArray();
        } else {
            $fileList = array();
        }
        $view['fileList'] = $fileList;

        return view('usercenter.my_file', $view);
    }

    /**
     * 用户中心 我的文件
     */
    public function memberFileEditOp($excute = 'view', $id = 0)
    {
        if (!in_array($excute, array('view', 'add', 'edit', 'del'))) {
            die('非法操作');
        }
        if (Request::Input('act_form') == 'add') {
            $data['title'] = Request::Input('filename');
            $data['guobie'] = Request::Input('guobie');
            $data['area'] = Request::Input('province') . '-' . Request::Input('city');
            $data['use'] = Request::Input('caryongtu');
            if ($data['use'] == '非营业企业客车') {
                $data['shenfen'] = Request::Input('typeqiye');
            } else {
                if (Request::Input('typetype') == 'local') {
                    $data['shenfen'] = '上牌地本市户籍居民';
                } else {
                    $data['shenfen'] = Request::Input('othertype');
                    if ($data['shenfen'] == '国内其他限牌城市户籍居民') {
                        $data['shenfen_extend'] = Request::Input('hujicity');
                    } elseif ($data['shenfen'] == '非中国大陆人士') {
                        $data['shenfen_extend'] = Request::Input('addressarea');
                    }
                }
            }

            $data['member_id'] = session('user.member_id');
            $data['username'] = $_SESSION['member_name'];
            $effectId = HgTellUs::insertGetId($data);
            if ($effectId > 0) {
                return redirect('/user/memberFile/view/' . $effectId);
            } else {
                die('数据保存失败，请重新再提交');

            }
        } elseif (Request::Input('act_form') == 'edit') {
            $data['title'] = Request::Input('filename');
            $data['guobie'] = Request::Input('guobie');
            $data['area'] = Request::Input('province') . '-' . Request::Input('city');
            $data['use'] = Request::Input('caryongtu');
            if ($data['use'] == '非营业企业客车') {
                $data['shenfen'] = Request::Input('typeqiye');
            } else {
                if (Request::Input('typetype') == 'local') {
                    $data['shenfen'] = '上牌地本市户籍居民';
                } else {
                    $data['shenfen'] = Request::Input('othertype');
                    if ($data['shenfen'] == '国内其他限牌城市户籍居民') {
                        $data['shenfen_extend'] = Request::Input('hujicity');
                    } elseif ($data['shenfen'] == '非中国大陆人士') {
                        $data['shenfen_extend'] = Request::Input('addressarea');
                    }
                }
            }

            $data['member_id'] = session('user.member_id');
            $data['username'] = session('user.member_name');
            $effect = HgTellUs::where('id', '=', $id)->update($data);
            if ($effect === false) {
                die('数据保存失败，请重新再提交');
            } else {
                return redirect('/user/memberFile/edit/' . $id);
            }
        }

        $view = ['title' => '会员中心-我的文件'];
        $view['flag'] = 'memberFile';


        $topArea = Area::getTopArea()->toArray();
        $view['topArea'] = $topArea;
        if ($excute == 'edit' || $excute == 'view') {
            $view['file'] = HgTellUs::getOneFileByUser($id, session('user.member_id'))->toArray();
            $memberArea = explode("-", $view['file']['area']);
        } elseif ($excute == 'add') {
            $memberInfo = HgUser::getMember(session('user.member_id'))->toArray();
            $memberArea = explode("-", $memberInfo['member_areainfo']);
            $view['file'] = array('title' => '', 'area' => $memberInfo['member_areainfo'], 'use' => '', 'guobie' => 0, 'shenfen' => '');
        } elseif ($excute == 'del') {
            $effectRow = HgTellUs::where(array('member_id' => session('user.member_id'), 'id' => $id))->delete();
            if ($effectRow) {
                return redirect('/user/memberFile/');
            } else {
                die('删除失败，请重新提交');
            }
        }

        if (empty($memberArea)) {
            $memberArea = array('province' => '', 'city' => '');
        } else {
            if (count($memberArea) == 1) {
                $memberArea = array('province' => $memberArea[0], 'city' => '');
            } else {
                $memberArea = array('province' => $memberArea[0], 'city' => $memberArea[1]);
            }
        }
        $view['memberArea'] = $memberArea;
        $view['excute'] = $excute;
        return view('usercenter.my_file_view', $view);
    }

    /**
     * 用户中心 我的特殊文件
     */
    public function memberSpecialFileOp()
    {
        $view = ['title' => '会员中心-我的特殊文件'];
        $view['flag'] = 'memberSpecialFile';
        return view('usercenter.my_special_file', $view);
    }

    /**
     * 用户中心 用户订单
     */
    public function memberOrderOp()
    {
        $userId = session('user.member_id');
        // 查询所有订单
        $view = ['title' => '会员中心-我的订单'];
        $view['flag'] = 'memberOrder';
        $map = array('buy_id' => $userId);

        $date = Input::get('date');
        $map['date'] = $date;
        $orderList = $this->order->getOrderListByUser($map);
        $configLang = config('orderstatus');
        $view['lang'] = $configLang;
        //print_r($configLang);exit;
        $view['orderList'] = $orderList;

        /*
         * 实际诚意金，担保金
         * @author Andy
         */
        $view['price'] = [];
        foreach ($orderList as $item) {
            // 获取订单担保金支付详细进度
            $money = $this->money->getOrderDopositDetail($userId, $item->id);

            $view['price'][$item->id] = [
                'price' => $item->bj_price, // 车价格包含服务费
                'earnest' => $item->bj_earnest_price, // 诚意金
                'guarantee' => $item->bj_car_guarantee, // 所有担保金金额(包含诚意金)
                'doposit' => $money['money'], // 实际支付担保金金额(不包含诚意金)
                'paidMoney' => $money['paidMoney'], // 已支付担保金金额
                'surplusMoney' => $money['surplusMoney'], // 未支付担保金金额
            ];
        }
        /*
         * END
         */

        return view('usercenter.order_index', $view);

    }

    /**
     * 用户验证 邮箱
     */
    public function getVerifyEmailOp($verify)
    {
        $data = $this->user->verifyEmail($verify, 'member_verify_email');
        
        if ($data['success'] && $data['verify'] == 1) {
            $dataVerifyInfo['email_verify'] = 2;
            DB::table("member_verify")->where(array('member_id' => session('user.member_id')))->update($dataVerifyInfo);
            Session::put('verify_status', 's1');
            return redirect()->route('user.member_info');
        }
    }

    /**
     * 验证邮箱  修改用户手机号码
     */
    public function getVerifyEmailToChangePhoneOp($verify)
    {
        $data = $this->user->verifyEmail($verify, 'change_phone_by_email');
        if ($data['success'] && $data['verify'] == 1) {
            Session::put('change_phone_status', 's1');//结合手机验证 变更手机号码，触发第一次
            return redirect('/user/memberSafe/phone_change_input');
        }
    }

    /**
     * 验证邮箱  修改用户密码
     */
    public function getVerifyEmailToChangePwdOp($verify)
    {
        $data = $this->user->verifyEmail($verify, 'send_email_to_change_passwd');
        if ($data['success'] && $data['verify'] == 1) {
            Session::put('verify_status', 's1');
            return redirect('/user/memberSafe/modify_password');
        }
    }
    
    /**
     * 产生验证码
     *makeCode
     */
    public function makecode(){
    	$seccode = makeSeccode();
    	Session::put('user_code', $seccode);
    	@header("Expires: -1");
    	@header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
    	@header("Pragma: no-cache");
    
    	$code = new Seccode();
    	$code->code = $seccode;
    	$code->width = 90;
    	$code->height = 26;
    	$code->background = 1;
    	$code->adulterate = 1;
    	$code->scatter = '';
    	$code->color = 1;
    	$code->size = 0;
    	$code->shadow = 1;
    	$code->animator = 0;
    	$code->datapath =  config('app.resource').'seccode/';
    	$code->display();
    }
    
    /**
     * AJAX验证
     *
     */
    public function checkcode(){
    	if (checkSeccode($_GET['captcha'])){
    		$data['error'] = '0';
    	}else{
    		$data['error'] = '1';
    	}
    	echo json_encode($data);
    }
}