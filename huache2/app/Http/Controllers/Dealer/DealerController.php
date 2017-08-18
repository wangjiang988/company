<?php
/**
 *
 *经销商用户中心
 *
 */
namespace App\Http\Controllers\Dealer;

use DB;
use Validator;
use Illuminate\Http\Request;
use Input;
use App\Com\Hwache\User\User;
use App\Http\Controllers\Controller;
use App\Models\HgSeller;
use Hash;
use App\Models\HgLock;
use App\Models\HgUser;
use App\Core\Contracts\Sms\Sms;
use Session;
use Mail;
use Crypt;
use App\Models\HgBaojia;
use App\Models\HgDealer;
use App\Models\HgDailiDealer;
use App\Models\HgWaiter;
use App\Models\HgBaoXian;
use App\Models\HgDealerBaoXian;
use App\Models\HgFields;
use App\Models\HgZengpin;
use App\Models\HgStandard;
use App\Models\HgDealerOtherPrice;

class DealerController extends Controller
{
    /**
     * 请求依赖
     * @var Request
     */
    private $request;

    /**
     * 用户中心模块依赖
     * @var User
     */
    private $user;

    /**
     * 构造函数，初始化内部依赖变量
     * @param Request $request
     * @param User $user
     */
    public function __construct(Request $request, User $user)
    {
        $this->middleware('auth.seller');
        $this->request = $request;
        $this->user = $user;
    }

    public function getIndex()
    {
        return redirect()->route('dealer.member_info');
    }

    /**
     *
     * 经销商用户基本资料
     */
    public function memberInfo($type = 'show')
    {
        $view['flag'] = 'memberInfo';
        $view['title'] = '经销商代理用户中心';
        $view['member'] = HgSeller::getProxy(session('user.seller_id'))->toArray();
        $area = config('area');
        if ($type == 'show') {
            $other_contact = DB::table('seller_contact')
                ->where('seller_id', session('user.seller_id'))
                ->get();
            $other_contact_str = '';
            if (count($other_contact) > 0) {
                $i = 1;
                foreach ($other_contact as $c) {
                    $other_contact_str .= $i . "." . $c->name . ":" . $c->phone . "&nbsp;&nbsp;&nbsp;&nbsp;";
                    $i++;
                }
            }
            $view['other_contact_str'] = $other_contact_str;
            return view('dealer.ucenter.member_info', $view);
        } elseif ($type == 'modify_base') {
            $other_contact = DB::table('seller_contact')
                ->where('seller_id', session('user.seller_id'))
                ->get();

            $view['other_contact'] = $other_contact;

            $view['province'] = $area[0];
            $view['areaProvinceStr'] = @$area[0][$view['member']['seller_province_id']]['name'];
            $view['areaCityStr'] = @$area[$view['member']['seller_province_id']][$view['member']['seller_city_id']]['name'];
            return view('dealer.ucenter.modify_base_info', $view);
        } elseif ($type == 'modify_bank') {
            $view['province'] = $area[0];
            $bankAreaArray = explode(" ", $view['member']['seller_bank_city_str']);
            if (count($bankAreaArray) == 2) {
                $bankAreaArray = array('province' => $bankAreaArray[0], 'city' => $bankAreaArray[1]);
            } else {
                $bankAreaArray = array('province' => '', 'city' => '');
            }
            $view['bankAreaArray'] = $bankAreaArray;
            return view('dealer.ucenter.modify_bank_info', $view);
        } else {
            die('非法操作');
        }

    }

    public function memberInfoPost($type)
    {
        $sellerInfo = HgSeller::getProxy(session('user.seller_id'))->toArray();
        if ($type == 'modify_base') {
            $data = array();
            $photo = $this->request->file('file');//上传头像
            $data = array(
                'seller_sex'         => $this->request->input('rdb-sex'),
                'seller_phone'       => $this->request->input('seller_phone'),
                'seller_province_id' => $this->request->input('province_id'),
                'seller_city_id'     => $this->request->input('city_id'),
                'seller_address'     => $this->request->input('seller_address'),
                'seller_postcode'    => $this->request->input('seller_postcode'),
                'seller_weixin'      => $this->request->input('seller_weixin'),
            );
            if (!empty($photo) && $photo->isValid()) {
                $entension = $photo->getClientOriginalExtension();
                if (!allowext($entension)) {
                    return response()->json(['error_code' => 0, 'msg' => '数据保存失败,图片格式错误！']);
                    // echo json_encode(array('error_code' => 1, 'msg' => '数据保存失败,图片格式错误！'));exit();
                }
                $fileName = session('user.member_id') . '_' . date('YmdHms') . mt_rand(1000, 9999) . '.' . $entension;
                $filePath = 'avatar/' . date("Y") . '/' . date("m") . '/';
                $photo->move(config('app.uploaddir') . $filePath, $fileName);
                $data['seller_photo'] = $filePath . $fileName;
                $orgin_avatar = $sellerInfo['seller_photo'];
                if (!empty($orgin_avatar)) {//needtodobyjerry modify path
                    // TODO 后续修改
                    @unlink(config('app.uploaddir') . $orgin_avatar);
                }
            }

            $e = HgSeller::where('seller_id', session('user.seller_id'))->update($data);
            if ($e === false) {
                   return response()->json(['error_code' => 0, 'msg' => '数据保存失败']);
            } else {
                  return response()->json(['error_code' => 1, 'msg' => '数据保存成功']);
            }
        } elseif ($type == 'modify_bank') {
            $data = array(
                'seller_bank_city_str' => $this->request->input('province') . ' ' . $this->request->input('city'),
                'seller_bank_addr'     => $this->request->input('address'),
                'seller_bank_account'  => $this->request->input('userCard'),
            );
            $photo['seller_bank_photo1'] = $this->request->file('bank_photo1');
            $photo['seller_bank_photo2'] = $this->request->file('bank_photo2');

            $filePath = 'seller_photo/' . date("Y") . '/' . date("m") . '/';

            foreach ($photo as $k => $p) {
                if (!empty($p) && $p->isValid()) {
                    $entension = $p->getClientOriginalExtension();
                    if (!allowext($entension)) {
                        return json_encode(array('error_code' => 1, 'msg' => '数据保存失败'));
                    }
                    $fileName = session('user.member_id') . '_' . date('YmdHms') . mt_rand(1000,
                            9999) . '.' . $entension;
                    $p->move(config('app.uploaddir') . $filePath, $fileName);
                    $data[$k] = $filePath . $fileName;
                    if (!empty($sellerInfo[$k])) {
                        //先删除原始的 再提交保存图片
                        @unlink(config('app.uploaddir') . $sellerInfo[$k]);
                    }
                }
            }

            $e = HgSeller::where('seller_id', session('user.seller_id'))->update($data);
            if ($e === false) {
                return json_encode(array('error_code' => 1, 'msg' => '数据保存失败'));
            } else {
                return json_encode(array('error_code' => 0, 'msg' => '数据保存成功'));
            }
        } elseif ($type == 'add-other-contact') {
            $data = array(
                'seller_id' => session('user.seller_id'),
                'store_id'  => $sellerInfo['store_id'],
                'name'      => $this->request->input('link-name'),
                'phone'     => $this->request->input('link-phone')
            );
            $e = DB::table('seller_contact')->insertGetId($data);
            if ($e === false) {
                return json_encode(array('error_code' => 1, 'msg' => '数据保存失败'));
            } else {
                return json_encode(array('error_code' => 0, 'msg' => '数据保存成功'));
            }
        } elseif ($type == 'del-other-contact') {
            $e = DB::table('seller_contact')
                ->where('id', $this->request->input('linkid'))
                ->where('seller_id', session('user.seller_id'))
                ->delete();
            if ($e === false) {
                return json_encode(array('error_code' => 1, 'msg' => '数据保存失败'));
            } else {
                return json_encode(array('error_code' => 0, 'msg' => '数据保存成功'));
            }
        }
    }

    /**
     *
     * 更改密码页面
     *
     */
    public function modifyPassword()
    {
        $view['flag'] = 'modifyPassword';
        $view['title'] = '会员中心-安全设置-修改密码-手机验证';
        $view['member'] = HgSeller::getProxy(session('user.seller_id'))->toArray();
        return view('dealer.ucenter.modify_password', $view);
    }

    /**
     *
     * 更改密码检查
     */

    public function modifyPasswordCheck()
    {
        $view['flag'] = 'modifyPassword';
        $code = $this->request->input('code');
        $cardnum = $this->request->input('card_num');
        $type = 2;
        if (session('login_code_time_' . $type) + 600 < time()) {
            return json_encode(array('error_code' => 0, 'error_msg' => '验证码失效'));
        }
        if ($code != session('login_code')) {
            return json_encode(array('error_code' => 2, 'error_msg' => '短信验证码输入有误'));
        }

        $member = HgSeller::getProxy(session('user.seller_id'))->toArray();
        $cardLastSixNumber = substr($member['seller_card_num'], -6);
        if ($cardLastSixNumber != $cardnum) {
            return json_encode(array('error_code' => 3, 'error_msg' => '身份证后六位输入有误'));
        }

        Session::put('login_code_status_' . $type, 'Y');
        return json_encode(array('error_code' => 1, 'error_msg' => '验证成功'));
    }

    /**
     *
     * 更改密码输入
     *
     */
    public function modifyPasswordInput()
    {
        $view['flag'] = 'modifyPassword';
        $view['title'] = '会员中心-安全设置-修改密码';
        $view['member'] = HgSeller::getProxy(session('user.seller_id'))->toArray();
        if (session('login_code_status_2') == 'Y') {
            return view('dealer.ucenter.modify_password_input', $view);
        } else {
            return redirect()->route('dealer.modify_password');
        }

    }

    /**
     *
     * 更改密码
     *
     */
    public function changePassword()
    {
        $view['flag'] = 'modifyPassword';
        $pwd = Hash::make($this->request->input('pwd'));
        $e = HgUser::where('member_id', session('user.member_id'))->update(array('member_passwd' => $pwd));
        if (!$e) {
            return json_encode(array('error_code' => 0, 'error_msg' => '密码更改失败'));
        } else {
            return json_encode(array('error_code' => 1, 'error_msg' => '密码更改成功'));
        }
    }

    /**
     *
     * 更改密码成功
     */
    public function changePasswordSuccess()
    {
        $view['flag'] = 'modifyPassword';
        $view['title'] = '会员中心-安全设置-修改密码成功';
        return view('dealer.ucenter.modify_password_success', $view);
    }

    /**
     *
     * 更改手机号码
     * @param number $type
     *
     */
    public function changeMobile($type)
    {
        $view['flag'] = 'memberInfo';
        $type_array = array();
        $view['member'] = HgSeller::getProxy(session('user.seller_id'))->toArray();
        if ($type == 'byphone' || $type == '') {
            $view['title'] = '会员中心-修改手机号码-手机验证';
            return view('dealer.ucenter.change_mobile_by_mobile', $view);
        } elseif ($type == 'byemail') {
            $view['title'] = '会员中心-修改手机号码-邮箱验证';
            return view('dealer.ucenter.change_mobile_by_email', $view);
        } elseif ($type == 'input') {
            if (session('login_code_status_3') == 'Y' || session('check_email_pass_status') == 'Y') {
                return view('dealer.ucenter.change_mobile_input', $view);
            } else {
                return redirect('/dealer/changemobile/byphone');

            }
        } elseif ($type == 'success') {
            if (session('login_code_status_4') != 'Y') {
                return redirect('/dealer/changemobile/input');
            } else {
                return view('dealer.ucenter.change_mobile_success', $view);
            }
        } elseif ($type == 'verify_email_to_change_phone') {
            $data = $this->request->input('data');
            $data = Crypt::decrypt($data);
            if ((config('mail.email_limit_time') * 60 + $data['time']) >= time() && $data['verify'] == 1) {
                Session::put('check_email_pass_status', 'Y');
                return redirect('/dealer/changemobile/input');
            } else {
                die('验证失败');
            }


        } elseif ($type == 'checkemailsuccess') {
            if (session('check_email_pass_status') == 'Y') {
                return view('dealer.ucenter.change_mobile_by_email_check_success', $view);
            } else {
                return redirect('/dealer/changemobile/byemail');
            }
        } elseif ($type == 'checkemailfailure') {
            return view('dealer.ucenter.change_mobile_by_email_check_failure', $view);

        }

    }

    /**
     *
     * 更改手机号码 post提交
     * @param number $type
     * @return json
     */
    public function postChangeMobile($type)
    {
        $view['flag'] = 'memberInfo';
        $type_array = array();
        $view['member'] = HgSeller::getProxy(session('user.seller_id'))->toArray();
        if ($type == 'checkcodebymobile') {
            $code = $this->request->input('code');
            $sendCodeType = $this->request->input('type');//获取的值为3

            if (session('login_code_time_' . $sendCodeType) + 600 < time()) {
                return json_encode(array('error_code' => 0, 'error_msg' => '验证码失效'));
            }
            if ($code != session('login_code')) {
                return json_encode(array('error_code' => 2, 'error_msg' => '短信验证码输入有误'));
            }
            Session::put('login_code_status_' . $sendCodeType, 'Y');
            return json_encode(array('error_code' => 1, 'error_msg' => '验证成功'));

        } elseif ($type == 'modifymobile') {
            $phone = $this->request->input('phone');
            $code = $this->request->input('code');
            $sendCodeType = $this->request->input('type');//获取的值为3

            if ($view['member']['member_mobile'] == $phone) {
                return json_encode(array('error_code' => 0, 'error_msg' => '更改号码和原始号码一致'));
            }
            if (session('login_code_time_' . $sendCodeType) + 600 < time()) {
                return json_encode(array('error_code' => 0, 'error_msg' => '验证码失效'));
            }
            if ($code != session('login_code')) {
                return json_encode(array('error_code' => 0, 'error_msg' => '短信验证码输入有误'));
            }

            Session::put('login_code_status_' . $sendCodeType, 'Y');
            $e = HgUser::where(array('member_id' => session('user.member_id')))->update(array('member_mobile' => $phone));
            if (!$e) {
                return json_encode(array('error_code' => 0, 'error_msg' => '手机号码更换失败'));
            }
            return json_encode(array('error_code' => 1, 'error_msg' => '验证成功'));
        } elseif ($type == 'checkcodebyemail') {
            $code = $this->request->input('code');//验证码
            $email = $this->request->input('email');//验证码
            if (!checkSeccode($code)) {
                return [
                    'error_code' => 0,
                    'error_msg'  => '验证码输入不正确',
                ];
            }
            $do = $this->sendEmail($email, 'send_email_to_change_mobile');
            if (!$do) {
                return [
                    'error_code' => 2,
                    'error_msg'  => '邮件发送失败',
                ];
            } else {
                return [
                    'error_code' => 1,
                    'error_msg'  => '邮件发送成功',
                ];
            }

        }

    }

    /**
     *
     * 更改email
     * @param number $type
     *
     */
    public function changeEmail($type)
    {
        $view['flag'] = 'memberInfo';
        $type_array = array();
        $view['member'] = HgSeller::getProxy(session('user.seller_id'))->toArray();
        if ($type == 'byphone' || $type == '') {
            $view['title'] = '会员中心-修改手机号码-手机验证';
            return view('dealer.ucenter.change_email_by_mobile', $view);
        } elseif ($type == 'byemail') {
            $view['title'] = '会员中心-修改手机号码-邮箱验证';
            return view('dealer.ucenter.change_email_by_email', $view);
        } elseif ($type == 'input') {
            if (session('login_code_status_5') == 'Y' || session('check_email_pass_status_orgin_email') == 'Y') {
                return view('dealer.ucenter.change_email_input', $view);
            } else {
                return redirect('/dealer/changeemail/byphone');

            }
        } elseif ($type == 'success') {
            if (session('login_code_status_5') != 'Y') {
                return redirect('/dealer/changeemail/input');
            } else {
                session::forget('login_code_status_5');//邮件更改成功 注销session
                session::forget('check_email_pass_status');//邮件更改成功 注销session
                return view('dealer.ucenter.change_email_success', $view);
            }
        } elseif ($type == 'verify_email_to_change_email') {
            $data = $this->request->input('data');
            $data = Crypt::decrypt($data);
            if ((config('mail.email_limit_time') * 60 + $data['time']) >= time() && $data['verify'] == 1) {
                Session::put('check_email_pass_status', 'Y');
                $e = HgSeller::where(array('member_id' => session('user.member_id')))->update(array('seller_email' => $data['email']));
                return redirect('/dealer/changeemail/success');
            } else {
                die('验证失败');
            }


        } elseif ($type == 'verify_email_to_check_email') {//检测原始邮箱
            $data = $this->request->input('data');
            $data = Crypt::decrypt($data);
            if ((config('mail.email_limit_time') * 60 + $data['time']) >= time() && $data['verify'] == 1) {
                Session::put('check_email_pass_status_orgin_email', 'Y');
                //$e = HgSeller::where(array('member_id'=>session('user.member_id')))->update(array('seller_email'=>$data['email']));
                return redirect('/dealer/changeemail/input');
            } else {
                die('验证失败');
            }


        } elseif ($type == 'checkemailsuccess') {
            if (session('check_email_pass_status') == 'Y') {
                return view('dealer.ucenter.change_email_by_email_check_success', $view);
            } else {
                return redirect('/dealer/changeemail/byemail');
            }
        } elseif ($type == 'checkemailfailure') {
            return view('dealer.ucenter.change_email_by_email_check_failure', $view);

        }

    }

    /**
     *
     * 更改email post提交
     * @param number $type
     * @return json
     */
    public function postChangeEmail($type)
    {
        $view['flag'] = 'memberInfo';
        $type_array = array();
        $view['member'] = HgSeller::getProxy(session('user.seller_id'))->toArray();
        if ($type == 'checkcodebymobile') {
            $code = $this->request->input('code');
            $sendCodeType = $this->request->input('type');//获取的值为5

            if (session('login_code_time_' . $sendCodeType) + 600 < time()) {
                return json_encode(array('error_code' => 0, 'error_msg' => '验证码失效'));
            }
            if ($code != session('login_code')) {
                return json_encode(array('error_code' => 2, 'error_msg' => '短信验证码输入有误'));
            }
            Session::put('login_code_status_' . $sendCodeType, 'Y');
            return json_encode(array('error_code' => 1, 'error_msg' => '验证成功'));

        } elseif ($type == 'checkcodebyorginemail') {
            $code = $this->request->input('code');//验证码
            $email = $this->request->input('email');//验证码
            if (!checkSeccode($code)) {
                return [
                    'error_code' => 0,
                    'error_msg'  => '验证码输入不正确',
                ];
            }
            $do = $this->sendEmail($email, 'send_email_to_check_orgin_email');
            if (!$do) {
                return [
                    'error_code' => 2,
                    'error_msg'  => '邮件发送失败',
                ];
            } else {
                //Session::put('check_email_pass_status_orgin_email', 'Y');//邮箱发送成功
                return [
                    'error_code' => 1,
                    'error_msg'  => '邮件发送成功',
                ];
            }

        } elseif ($type == 'checkcodebyemail') {
            $code = $this->request->input('code');//验证码
            $email = $this->request->input('email');//验证码
            if (!checkSeccode($code)) {
                return [
                    'error_code' => 0,
                    'error_msg'  => '验证码输入不正确',
                ];
            }
            if ($view['member']['seller_email'] == $email) {
                return [
                    'error_code' => 3,
                    'error_msg'  => '该邮箱地址已经存在',
                ];
            }
            $do = $this->sendEmail($email, 'send_email_to_change_email');
            if (!$do) {
                return [
                    'error_code' => 2,
                    'error_msg'  => '邮件发送失败',
                ];
            } else {
                Session::put('check_email_pass_status', 'Y');//邮箱发送成功
                Session::put('need_to_modify_email', $email);//邮箱发送成功
                return [
                    'error_code' => 1,
                    'error_msg'  => '邮件发送成功',
                ];
            }

        }

    }

    /**
     * 常用管理增加经销商 get
     * $type  add|edit
     * $id 报价的ID,add时自动检测是否有没有完成的报价
     * $step  主要是供type=edit时 处理条件
     */
    public function editDealer($type, $id = 0, $step = 'step0')
    {
        $view['flag'] = 'editDealer';
        $view['id'] = $id;
        $view['step'] = $step;//来自url传过来的步骤
        $view['type'] = $type;//来自url传过来的类型 add or  edit

        if ($type == 'add') {
            $daili = HgDailiDealer::where('dl_id', session('user.member_id'))
                ->where('dl_step', '<=', 9)
                ->where('dl_status', '<>', 3)
                ->orderBy('id', 'desc')
                ->first();//检查报价是否存在
            if (empty($daili)) {
                $step = 'step0';
            } else {
                $daili = $daili->toArray();
                $step = intval($daili['dl_step']);
                if ($step >= 1 && $step <= 9) {//检查报价是否完成，没有完成找到当前的状态 直接跳转，以数据库为准
                    $step = 'step' . $step;
                }
            }
            $template = '_add';//模板调用的名称包含字符
        } elseif ($type == 'edit') {
            if ($id > 0) {
                $daili = HgDailiDealer::where('id', $id)
                    ->where('dl_id', session('user.member_id'))
                    ->where('dl_status', '<>', 3)
                    ->first();//检查报价是否存在
                $view['daili_dealer_id'] = $id;
                if (empty($daili)) {
                    die('该代理经销商不存在');
                } else {
                    $daili = $daili->toArray();
                }
                //$daili['dl_status'] = 2;//调试开关
                if ($daili['dl_status'] == 2) {
                    $template = '_edit';//模板调用的名称包含字符
                    $view['flag'] = 'information' . $view['daili_dealer_id'];
                }
                if ($daili['dl_status'] == 1 && $daili['dl_step'] == 10) {
                    $template = '_check';
                    $view['flag'] = 'information' . $view['daili_dealer_id'];
                } else {
                    $view['flag'] = 'information' . $view['daili_dealer_id'];
                    $template = '_edit';//模板调用的名称包含字符
                    if (substr($step, 4) > $daili['dl_step']) {//如果没有添加完成，则跳转到最后的步骤
                        return redirect('/dealer/editdealer/edit/' . $id . '/step' . $daili['dl_step']);
                    }
                }
            } else {//新增代理
                return redirect('/dealer/editdealer/add/0');
            }
        } elseif ($type == 'check') {
            // return view()
            $template = '_add';
            $daili = HgDailiDealer::where('id', $id)
                ->where('dl_id', session('user.member_id'))
                ->where('dl_status', '<>', 3)
                ->first();//检查报价是否存在
            if (substr($step, 4) >= $daili['dl_step']) {
                return redirect('/dealer/editdealer/add/0');
            }
        } else {
            die('非法操作');
        }

        $view['daili'] = $daili;

        if ($step == 'step0') {
            $allCar = config('car');
            $tmp = $allCar[0];
            $goods_class = array();
            foreach ($tmp as $k => $v) {
                $goods_class[] = $v;
            }
            $view['brand'] = $goods_class;

            $allArea = config('area.0');
            $view['area'] = $allArea;
            if ($type == 'edit' || $type == 'check') {
                $view['dealer'] = HgDealer::where('d_id', $daili['d_id'])->first()->toArray();
                $view['car_brand'] = $tmp[$daili['dl_brand_id']];
            }
            return view('dealer.ucenter.dealer' . $template . '_step0', $view);
        } elseif ($step == 'step1') {
            $search_value = trim($this->request->input('search_value'));
            $daili_dealer_id = HgDailiDealer::getDailiDealerId($daili['dl_id'], $daili['d_id']);
            if ($search_value != '') {
                $waitor = HgWaiter::where('agent_id', session('user.member_id'))
                    ->where('dealer_id', $daili['d_id'])
                    ->where('name', 'like', '%' . $search_value . '%')
                    ->where('daili_dealer_id', $daili_dealer_id)
                    ->get();
                $view['search_value'] = $this->request->input('search_value');
            } else {
                $waitor = HgDailiDealer::getHgWaiter(session('user.member_id'), $daili['d_id']);
                $view['search_value'] = '';
            }
            $view['waitor'] = $waitor;
            $view['dealer_id'] = $daili['d_id'];
            $view['dl_id'] = $daili['dl_id'];
            $view['id'] = $daili['id'];
            return view('dealer.ucenter.dealer' . $template . '_step1', $view);
        } elseif ($step == 'step2') {
            $allBaoxian = HgBaoXian::get();
            if (!empty($allBaoxian)) {
                $allBaoxian = $allBaoxian->ToArray();
            } else {
                $allBaoxian = array();
            }
            $view['allBaoxian'] = $allBaoxian;
            $myBaoxian = HgDealerBaoXian::getDealerBaoXianAllInfoList(session('user.member_id'), $daili['d_id']);
            $myBaoxian = !empty($myBaoxian) ? $myBaoxian->toArray() : array();
            $view['myBaoxian'] = $myBaoxian;
            $view['dealer_id'] = $daili['d_id'];
            $view['id'] = $daili['id'];
            return view('dealer.ucenter.dealer' . $template . '_step2', $view);
        } elseif ($step == 'step3') {
            $view['id'] = $daili['id'];
            $view['dealer_id'] = $daili['d_id'];
            //dd($view);
            return view('dealer.ucenter.dealer' . $template . '_step3', $view);
        } elseif ($step == 'step4') {
            $view['id'] = $daili['id'];
            $view['dealer_id'] = $daili['d_id'];
            $view['dl_linpai_fee'] = $daili['dl_linpai_fee'];
            $view['dl_linpai'] = $daili['dl_linpai'];
            return view('dealer.ucenter.dealer' . $template . '_step4', $view);
        } elseif ($step == 'step5') {
            $brand_id = $daili['dl_brand_id'];
            $zengpin = HgZengpin::get();
            //$zengpin = HgZengpin::where('brand_id',$brand_id)->get();
            if (!empty($zengpin)) {
                $zengpin = $zengpin->toArray();
            } else {
                $zengpin = array();
            }
            $view['zengpin'] = $zengpin;
            $myZengpin = DB::table('hg_dealer_zengpin')
                ->join("hg_zengpin", 'hg_dealer_zengpin.zp_id', '=', 'hg_zengpin.id')
                ->join('hg_daili_dealer', 'hg_dealer_zengpin.daili_dealer_id', '=', 'hg_daili_dealer.id')
                ->where('hg_daili_dealer.dl_status', '<>', 3)
                ->where('hg_dealer_zengpin.dl_id', session('user.member_id'))
                ->where('hg_dealer_zengpin.dealer_id', $daili['d_id'])
                ->select('hg_dealer_zengpin.*', 'hg_zengpin.title')
                ->get();
            $view['myZengpin'] = $myZengpin;
            $view['id'] = $daili['id'];
            $view['dealer_id'] = $daili['d_id'];
            //dd($view)
            return view('dealer.ucenter.dealer' . $template . '_step5', $view);
        } elseif ($step == 'step6') {
            $view['id'] = $daili['id'];
            $view['dealer_id'] = $daili['d_id'];
            //先去经销商的用户表中去判断是否存在杂费
            $myzafei = HgDealerOtherPrice::getOther(session('user.member_id'), $daili['d_id']);
            if (!empty($myzafei)) {
                $myzafei = $myzafei;
            } else {
                $myzafei = array();
            }
            $view['myzafei'] = $myzafei;
            //不存在就到总的后台去读取所有的杂费
            $zafei = HgFields::getFieldsName('other_price');  //获取杂费信息
            $view['zafei'] = $zafei->toArray();
            return view('dealer.ucenter.dealer' . $template . '_step6', $view);
        } elseif ($step == 'step7') {
            $data = HgStandard::getNorm(session('user.member_id'), $daili['id']);
            if (!empty($data)) {
                $data = $data->toArray();
            } else {
                $data = array();
            }
            $view['id'] = $daili['id'];
            $view['dealer_id'] = $daili['d_id'];
            return view('dealer.ucenter.dealer' . $template . '_step7', $view, $data);
        } elseif ($step == 'step8') {
            $data = HgStandard::getNorm(session('user.member_id'), $daili['id']);
            if (!empty($data)) {
                $data = $data->toArray();
            } else {
                $data = array();
            }
            $view['id'] = $daili['id'];
            $view['dealer_id'] = $daili['d_id'];
            return view('dealer.ucenter.dealer' . $template . '_step8', $view, $data);
        } elseif ($step == 'step9') {
//			$competitor = '';
            $view['id'] = $daili['id'];
            $view['dealer_id'] = $daili['d_id'];
            $allArea = config('area.0');
            $view['area'] = $allArea;
            $view['analysis'] = HgDailiDealer::getAnalysis($daili['d_id'], $daili['dl_id']);
            //dd($view);

            return view('dealer.ucenter.dealer' . $template . '_step9', $view);
        }
    }

    /**
     * 常用管理 增加经销商 post
     */
    public function postEditDealer($type, $id, $step)
    {//全部ajax提交
        if ($type == 'add') {
            if ($id == 0) {
                $d_id = $this->request->input('d_id');
                $dl_id = session('user.member_id');
                $has = HgDailiDealer::repDealer($d_id, $dl_id);
                if (count($has) >= 1) {
                    return [
                        'error_code' => 2,
                        'error_msg'  => '经销商已存在'
                    ];
                }
                $d_sort_name = $this->request->input('txt-dealers-shot-name');
                $dl_type = $this->request->input('dl_type');
                $brand_id = $this->request->input('brand_id');
                $bank_addr = $this->request->input('bank_addr');
                $bank_account = $this->request->input('bank_account');
                $txtcode = $this->request->input('txtcode');
                $store_id = session('user.store_id');
                $data = array(
                    'd_id'            => $d_id,
                    'dl_id'           => $dl_id,
                    'd_shortname'     => $d_sort_name,
                    'dl_type'         => $dl_type,
                    'dl_brand_id'     => $brand_id,
                    'dl_bank_addr'    => $bank_addr,
                    'dl_bank_account' => $bank_account,
                    'dl_code'         => $txtcode,
                    'dl_store_id'     => $store_id,
                    'dl_step'         => 1,
                    'dl_status'       => 1,
                );
                $e = HgDailiDealer::insertGetId($data);

            }
        } elseif ($type == 'edit') {
            if ($step == 'step2') {
                $updata = array('dl_baoxian' => $this->request->input('baoxian'));
                $e = HgDailiDealer::where("id", $id)
                    ->where('dl_id', session('user.member_id'))
                    ->update($updata);
            }
        }

        if (!$e) {
            return [
                'error_code' => 0,
                'error_msg'  => '添加失败',
            ];
        } else {
            return [
                'error_code' => 1,
                'error_msg'  => '添加成功',
            ];
        }

    }

    /**
     *
     * @param  $type
     * @param  $id
     *
     */

    public function ajaxSubmitDealer($type, $dealer_id = 0)
    {
        $dl_id = session('user.member_id');
        if ($type == 'add-waitor') {//添加服务专员
            $id = $this->request->input('id');
            $name = $this->request->input('specialist-name');
            $phone = $this->request->input('specialist-phone');
            $tel = $this->request->input('specialist-tel');
            $remark = e(str_replace("\n", "", $this->request->input('specialist-remark')));
            $updata = array(
                'daili_dealer_id' => $id,
                'dealer_id'       => $dealer_id,
                'agent_id'        => $dl_id,
                'name'            => $name,
                'mobile'          => $phone,
                'tel'             => $tel,
                'empirical'       => 0,
                'notice'          => $remark,

            );
            $e = HgWaiter::insertGetId($updata);
        } elseif ($type == 'edit-waitor') {

            $id = $this->request->input('specialist-id');
            $name = $this->request->input('specialist-name');
            $phone = $this->request->input('specialist-phone');
            $tel = $this->request->input('specialist-tel');
            $remark = e(str_replace("\n", "", $this->request->input('specialist-remark')));
            $updata = array(
                'name'   => $name,
                'mobile' => $phone,
                'tel'    => $tel,
                'notice' => $remark,

            );
            $e = HgWaiter::where('id', $id)
                ->where('dealer_id', $dealer_id)
                ->where('agent_id', $dl_id)
                ->update($updata);

        } elseif ($type == 'del-waitor') {
            $id = $this->request->input('id');
            $e = HgWaiter::where('id', $id)
                ->where('dealer_id', $dealer_id)
                ->where('agent_id', $dl_id)
                ->delete();
        } elseif ($type == 'add-fees') {       //添加杂费部分
            $other['daili_id'] = $dl_id;
            $other['dealer_id'] = $this->request->input('dealer_id');
            $other['other_id'] = $this->request->input('id');
            $other['other_price'] = $this->request->input('price');
            $other['daili_dealer_id'] = $this->request->input('daili_dealer_id');

            $other['other_name'] = HgFields::getTitle($other['other_id'])['title'];
            //判断是否重复添加杂费
            $daili_dealer_id = HgDailiDealer::getDailiDealerId($dl_id, $other['dealer_id']);
            $hg_other = HgDealerOtherPrice::judge($other['other_id'], $other['dealer_id'], $dl_id, $daili_dealer_id);
            if (empty($hg_other)) {
                $other_id = HgDealerOtherPrice::insertGetId($other);
                return [
                    'error_code' => 1,
                    'id'         => $other_id,
                    'deaer'      => $other['dealer_id'],
                ];
            } else {
                return [
                    'error_code' => 0,
                    'error_msg'  => '您添加的项目已经存在，不能重复~'
                ];
            }
        } elseif ($type == 'editzengpin') {
            $dl_zp_id = $this->request->input('dl_zp_id');
            $data['dl_zp_num'] = $this->request->input('num');
            $dealer_id = $this->request->input('dealer_id');
            $dl_id = $dl_id;
            $data['zp_id'] = $this->request->input('zp_id');
            $daili_dealer_id = HgDailiDealer::getDailiDealerId($dl_id, $dealer_id);
            $checkExist = DB::table('hg_dealer_zengpin')
                ->where('dealer_id', $dealer_id)
                ->where('dl_id', $dl_id)
                ->where('daili_dealer_id', $daili_dealer_id)
                ->where('zp_id', $data['zp_id'])
                ->where('dl_zp_id', '<>', $dl_zp_id)
                ->get();
            if (count($checkExist) >= 1) {
                return [
                    'error_code' => '0',
                    'error_msg'  => '您添加的项目已经存在，不能重复~'
                ];
            }
            $updata = DB::table('hg_dealer_zengpin')
                ->where('dealer_id', $dealer_id)
                ->where('dl_id', $dl_id)
                ->where('dl_zp_id', $dl_zp_id)
                ->update($data);
            if ($updata) {
                return [
                    'error_code' => '1',
                    'error_msg'  => '恭喜，修改成功！'
                ];
            } else {
                return [
                    'error_code' => '0',
                    'error_msg'  => '抱歉！修改失败，请重新尝试~'
                ];
            }
        } elseif ($type == 'editzafei') {
            $id = $this->request->input('id');
            $data['other_id'] = $this->request->input('other_id');
            $dealer_id = $this->request->input('dealer_id');
            $data['other_price'] = $this->request->input('price');
            $daili_dealer_id = HgDailiDealer::getDailiDealerId($dl_id, $dealer_id);
            $checkExist = HgDealerOtherPrice::where('dealer_id', $dealer_id)
                ->where('daili_id', $dl_id)
                ->where('other_id', $data['other_id'])
                ->where('daili_dealer_id', $daili_dealer_id)
                ->where('id', '<>', $id)
                ->get();
            if (count($checkExist) >= 1) {
                return [
                    'error_code' => 0,
                    'error_msg'  => '您添加的项目已经存在，不能重复~',
                ];
            }
            $data = DB::table("hg_dealer_other_price")
                ->where('dealer_id', $dealer_id)
                ->where('daili_id', $dl_id)
                ->where('id', $id)
                ->update($data);
            if ($data) {
                return [
                    'error_code' => '1',
                    'error_msg'  => '恭喜，修改成功！'
                ];
            } else {
                return [
                    'error_code' => '0',
                    'error_msg'  => '抱歉！修改失败，请重新尝试~'
                ];
            }
        } elseif ($type == 'add-dealer') {
            $data = [
                'dealer_name' => $this->request->input('dealer_name'),
                'brand'       => $this->request->input('txtbrand'),
                'province'    => $this->request->input('province'),
                'city'        => $this->request->input('city'),
                'dl_id'       => $dl_id,
                'type'        => '1'
            ];
            //代理商申请经销商信息
            $checkData = DB::table('hg_dealer_project_get')->insertGetId($data);
            if ($checkData) {
                return [
                    'error_code' => 1
                ];
            } else {
                return [
                    'error_code' => 0
                ];
            }

        } elseif ($type == 'add-project') {
            $daili_dealer_id = HgDailiDealer::getDailiDealerId($dl_id, $dealer_id);
            $data = [
                'project_name'    => $this->request->input('title'),
                'type'            => $this->request->input('type'),
                'dl_id'           => $dl_id,
                'dealer_id'       => $dealer_id,
                'daili_dealer_id' => $daili_dealer_id
            ];
            $checkData = DB::table('hg_dealer_project_get')->insertGetId($data);
            if ($checkData) {
                return [
                    'error_code' => 1
                ];
            } else {
                return [
                    'error_code' => 0
                ];
            }
        } elseif ($type == 'next-step') {
            $id = $this->request->input('id');
            $step = $this->request->input('step');
            $daili = HgDailiDealer::where('id', $id)
                ->where('dl_id', $dl_id)
                ->first();
            $standards = HgStandard::where('daili_id', $id)
                ->where('dealer_id', session('user.member_id'))
                ->first();
            if ($daili['dl_step'] > $step && $step) {
                if ($step == 2) {
                    $updata['dl_baoxian'] = $this->request->input('bx_type');
                    $e = HgDailiDealer::where('id', $id)->where('dl_id', $dl_id)->update($updata);
                }
                return array('type' => 'check');
            }
            //审核未通过,再次提交审核
            if ($daili['dl_step'] == 10 && $daili['dl_status'] == 4) {
                $updata['dl_status'] = 1;
                $updata['dl_step'] = 10;
                $e = HgDailiDealer::where('id', $id)->where('dl_id', $dl_id)->update($updata);
                if ($e) {
                    return [
                        'error_code' => '10',
                        'code_id'    => $id,
                    ];
                } else {
                    return [
                        'error_code' => '1'
                    ];
                }
            }
            if (!empty($daili) && $daili['dl_step'] >= 1 && $daili['dl_step'] <= 9) {
//                $nextStep = $daili['dl_step']+1;
//				$updata = array('dl_step'=>$nextStep);
                //echo $daili['dl_step'];exit;
                //1当前为专员，下一步是保险

                if ($daili['dl_step'] == 1) {
                    $nextStep = $daili['dl_step'] + 2;
                } else {
                    if ($daili['dl_step'] == 2) {//保险
                        $updata['dl_baoxian'] = $this->request->input('bx_type');
                        $nextStep = $daili['dl_step'] + 1;
                    } elseif ($daili['dl_step'] == 3) {
                        $updata['dl_shangpai'] = $this->request->input('dl_shangpai'); //获取代理上牌状态
                        $updata['dl_shangpai_fee'] = intval($this->request->input('dl_shangpai_fee'));
                        $updata['dl_shangpai_object'] = $this->request->input('dl_shangpai_object'); //获取赔偿状态
                        if ($updata['dl_shangpai_object'] == 1) {
                            $updata['dl_shangpai_object_fee'] = intval($this->request->input('dl_shangpai_object_fee'));
                        } else {
                            $updata['dl_shangpai_object_fee'] = 0;
                        }
                        $nextStep = $daili['dl_step'] + 1;

                    } elseif ($daili['dl_step'] == 4) {//上临牌操作
                        $updata['dl_linpai'] = $this->request->input('dl_linpai');//代理上牌
                        $updata['dl_linpai_fee'] = $this->request->input('dl_linpai_fee');
                        $nextStep = $daili['dl_step'] + 1;
                    } elseif ($daili['dl_step'] == 5) {//免费提供
                        $nextStep = $daili['dl_step'] + 1;
                    } elseif ($daili['dl_step'] == 6) {//杂费标准
                        $nextStep = $daili['dl_step'] + 1;
                    } elseif ($daili['dl_step'] == 7) {//刷卡标准
                        if ($standards) {
                            $standards = $standards->toArray();
                            $standard_id = $standards['id'];
                        } else {
                            $standard_id = HgStandard::insertGetId([
                                'daili_id'  => $id,
                                'dealer_id' => session('user.member_id')
                            ]);
                        }
                        $standard['xyk_status'] = $this->request->input('xyk_status'); //信用卡
                        $standard['jjk_status'] = $this->request->input('jjk_status');  //借记卡
                        if ($standard['xyk_status'] == 1) {
                            $standard['xyk_number'] = $this->request->input('xyk_number'); //次数
                            $xyk_per = $this->request->input('xyk_per');   //百分比
                            $xyk_yuan = $this->request->input('xyk_yuan'); //每次扣多少元
                            if ($xyk_per == 1) {
                                $standard['xyk_per_num'] = $this->request->input('xyk_per_num');
                            }
                            if ($xyk_yuan == 1) {
                                $standard['xyk_yuan_num'] = intval($this->request->input('xyk_yuan_num'));
                            }
                        }
                        if ($standard['jjk_status'] == 1) {
                            $standard['jjk_number'] = $this->request->input('jjk_number'); //次数
                            $jjk_per = $this->request->input('jjk_per');   //百分比
                            $jjk_yuan = $this->request->input('jjk_yuan'); //每次扣多少元
                            if ($jjk_per == 1) {
                                $standard['jjk_per_num'] = $this->request->input('jjk_per_num');
                            }
                            if ($jjk_yuan == 1) {
                                $standard['jjk_yuan_num'] = intval($this->request->input('jjk_yuan_num'));
                            }
                        }
                        $nextStep = $daili['dl_step'] + 2;
                        HgStandard::where('id', $standard_id)->update($standard);
                        // HgStandard::where('dealer_id',session('user.member_id'))->where('daili_id',$id)->update($standard);

                    } elseif ($daili['dl_step'] == 8) {//补贴情况
                        $standards = $standards->toArray();
                        $standard_id = $standards['id'];
                        $standard['bt_status'] = $this->request->input('bt_status');
                        if ($standard['bt_status'] == 1) {  //国家提供节能补贴
                            $time_status = $this->request->input('bt_time_status');
                            if ($time_status == 1) {
                                $standard['bt_work_day'] = $this->request->input('work_day');
                            }
                            if ($time_status == 2) {
                                $standard['bt_work_month'] = $this->request->input('work_month');
                            }
                        }
                        $standard['bt_gov'] = $this->request->input('bt_gov'); //获取地方政府补贴状态
                        $standard['bt_factory'] = $this->request->input('bt_factory');  //厂家或经销商置换补贴
                        $nextStep = $daili['dl_step'] + 1;
                        HgStandard::where('id', $standard_id)->update($standard);

                    } elseif ($daili['dl_step'] == 9) {//竞争分析
                        $nextStep = $daili['dl_step'] + 1;
                        if ($daili['dl_competitor_dealer_id_1'] == '' || $daili['dl_competitor_dealer_id_2'] == '') {
                            return [
                                'error_code' => '0',
                                'error_msg'  => '竞争关系的4S店必须有2家！找不到请把范围扩大到相邻地区~'
                            ];
                        }
                    }
                }
                $updata['dl_step'] = $nextStep;
                $updata['dl_add_time'] = time();
                $e = HgDailiDealer::where('id', $id)->where('dl_id', $dl_id)->update($updata);
                if ($nextStep == 10) {
                    return [
                        'error_code' => '10',
                        'code_id'    => $id,
                    ];
                } else {
                    if ($e) {
                        return [
                            'error_code' => '1'
                        ];
                    }
                }

            } else {
                $e = false;
            }
        } elseif ($type == 'add-new-present') {
            $zhenpin['status'] = 0;                    //用户提交的状态默认为0;
            $zhenpin['title'] = $this->request->input('title');     //客户提交新的赠品名称到后台
            return HgZengpin::insertGetId($zhenpin);
        } elseif ($type == 'add-new-incidental') {
            $incidental = $this->request->input('txtname');     //客户提交新的杂费到后台
        } elseif ($type == 'save-step') {
            $id = $this->request->input('id');
            $step = $this->request->input('step');
            $types = $this->request->input('type', 'edit');
            //查出杂费
            $standards = HgStandard::where('daili_id', $id)
                ->where('dealer_id', session('user.member_id'))
                ->first();
            if ($step == 0) {
                $updata['dl_code'] = $this->request->input('txtcode');
                $updata['dl_bank_addr'] = $this->request->input('bank');
                $updata['dl_bank_account'] = $this->request->input('account');
            }

            if ($step == 2) {//保险
                $updata['dl_baoxian'] = $this->request->input('bx_type');
            } elseif ($step == 3) {//上牌操作
                $updata['dl_shangpai'] = $this->request->input('dl_shangpai'); //获取代理上牌状态
                $updata['dl_shangpai_fee'] = intval($this->request->input('dl_shangpai_fee'));
                $updata['dl_shangpai_object'] = $this->request->input('dl_shangpai_object'); //获取赔偿状态
                if ($updata['dl_shangpai_object'] == 1) {
                    $updata['dl_shangpai_object_fee'] = intval($this->request->input('dl_shangpai_object_fee'));
                }

            } elseif ($step == 4) {//上临牌操作
                $updata['dl_linpai'] = $this->request->input('dl_linpai');
                $updata['dl_linpai_fee'] = $this->request->input('dl_linpai_fee');
            } elseif ($step == 5) {//免费提供

            } elseif ($step == 6) {//杂费标准

            } elseif ($step == 7) {//刷卡标准
                $standard_id = $standards['id'];
                $standard['xyk_status'] = $this->request->input('xyk_status'); //信用卡
                $standard['jjk_status'] = $this->request->input('jjk_status');  //借记卡
                if ($standard['xyk_status'] == 1) {
                    $standard['xyk_number'] = $this->request->input('xyk_number'); //次数
                    $xyk_per = $this->request->input('xyk_per');   //百分比
                    $xyk_yuan = $this->request->input('xyk_yuan'); //每次扣多少元
                    if ($xyk_per == 1) {
                        $standard['xyk_per_num'] = $this->request->input('xyk_per_num');
                    } else {
                        $standard['xyk_per_num'] = 0;
                    }
                    if ($xyk_yuan == 1) {
                        $standard['xyk_yuan_num'] = intval($this->request->input('xyk_yuan_num'));
                    } else {
                        $standard['xyk_yuan_num'] = 0;
                    }
                }
                if ($standard['jjk_status'] == 1) {
                    $standard['jjk_number'] = $this->request->input('jjk_number'); //次数
                    $jjk_per = $this->request->input('jjk_per');   //百分比
                    $jjk_yuan = $this->request->input('jjk_yuan'); //每次扣多少元
                    if ($jjk_per == 1) {
                        $standard['jjk_per_num'] = $this->request->input('jjk_per_num');
                    } else {
                        $standard['jjk_per_num'] = 0;
                    }
                    if ($jjk_yuan == 1) {
                        $standard['jjk_yuan_num'] = intval($this->request->input('jjk_yuan_num'));
                    } else {
                        $standard['jjk_yuan_num'] = 0;
                    }
                }
                // dd($standard_id);
                if (empty($standard_id)) {
                    $standard['dealer_id'] = session('user.member_id');
                    $standard['daili_id'] = $id;
                    return HgStandard::insertGetId($standard);
                } else {
                    $e = HgStandard::where('id', $standard_id)->update($standard);
                    if ($types == 'check') {
                        return [
                            'step' => $step
                        ];
                    } else {
                        return ['error_code' => 1];
                    }
                }


            } elseif ($step == 8) {//补贴情况
                $standard_id = $standards['id'];
                $standard['bt_status'] = $this->request->input('bt_status');
                if ($standard['bt_status'] == 1) {  //国家提供节能补贴
                    $time_status = $this->request->input('bt_time_status');
                    if ($time_status == 1) {
                        $standard['bt_work_day'] = $this->request->input('work_day');
                        $standard['bt_work_month'] = 0;
                    }
                    if ($time_status == 2) {
                        $standard['bt_work_month'] = $this->request->input('work_month');
                        $standard['bt_work_day'] = 0;
                    }
                } else {
                    $standard['bt_work_day'] = 0;
                    $standard['bt_work_month'] = 0;
                }
                $standard['bt_gov'] = $this->request->input('bt_gov'); //获取地方政府补贴状态
                $standard['bt_factory'] = $this->request->input('bt_factory');  //厂家或经销商置换补贴
                HgStandard::where('id', $standard_id)->update($standard);
                if ($types == 'check') {
                    return [
                        'step' => $step
                    ];
                } else {
                    return ['error_code' => 1];
                }

            } elseif ($step == 9) {//竞争分析

            }
            $e = HgDailiDealer::where('id', $id)->where('dl_id', $dl_id)->update($updata);

            if (($e) && ($types == 'edit')) {
                return [
                    'error_code' => '1',
                    'error_msg'  => '操作成功'
                ];
            }

            if ($types == 'check') {
                return [
                    'step' => $step
                ];
            }
            return ['error_code' => 0];

        } elseif ($type == 'citylist') {
            $caid = $this->request->input('id');
            $allArea = config('area.' . $caid);
            $data = array();
            foreach ($allArea as $k => $value) {
                $tmp = ['id' => $k, 'name' => $value['name']];
                array_push($data, $tmp);
            }
            return $data;
        } elseif ($type == 'list') {
            $id = $this->request->input('id');
            $data = HgDealer::where('d_shi', $id)->get();
            return json_encode($data);
        } elseif ($type == 'cityedit') {

        } elseif ($type == 'analysis') {
            $id = $this->request->input('id');
            $comp_id = $this->request->input('d_id');
            $d_shi = $this->request->input('d_shi');
            $result = ['error_code' => 0, 'error_msg' => '添加失败'];
            if (empty($comp_id) || empty($d_shi)) {
                return $result;
            }
            $dealer_id = $this->request->input('dealer_id');
            $dl_type = $this->request->input('dl_type');
            $data = [
                'dl_competitor_dealer_id_' . $dl_type      => $comp_id,
                'dl_competitor_dealer_area_id_' . $dl_type => $d_shi,
                'dl_status' => 1
            ];
            //判重 NeedToDo
            $info = HgDailiDealer::where('id', $id)->where('dl_id', $dl_id)->where('d_id', $dealer_id)->first();
            if ($dl_type == 1 && $info->dl_competitor_dealer_id_2 == $comp_id) {
                return ['error_code' => 0, 'error_msg' => '两个经销商不能相同，请重新选择~'];
            } elseif ($dl_type == 2 && $info->dl_competitor_dealer_id_1 == $comp_id) {
                return ['error_code' => 0, 'error_msg' => '两个经销商不能相同，请重新选择~'];
            }
            //判重自己不能添加自己
            if ($comp_id == $dealer_id) {
                return ['error_code' => 0, 'error_msg' => '不能添加自己为竞争对手，请重新选择~'];
            }

            $checkData = HgDailiDealer::where('id', $id)->where('dl_id', $dl_id)->where('d_id',
                $dealer_id)->update($data);
            if ($checkData) {
                return [
                    'error_code' => 1,
                    'error_msg'  => '添加成功'
                ];
            } else {
                return $result;
            }

        } elseif ($type == 'del-dealer') {     //删除经销商
            $dealer_id = $this->request->input('id');
            return HgDailiDealer::deleteDealer($dl_id, $dealer_id);
        } elseif ($type == 'add-baoxian') {
            $id = $this->request->input('id');//主键ID
            $co_id = $this->request->input('co_id');//保险公司ID
            $dealer_id = $this->request->input('dealer_id');
            $daili_dealer_id = $this->request->input('daili_dealer_id');
            $checkExist = HgDealerBaoXian::where('m_id', $dl_id)
                ->where('co_id', $co_id)
                ->where('dealer_id', $dealer_id)
                ->where('daili_dealer_id', $daili_dealer_id)
                ->where('id', '<>', $id)
                ->get();
            if (count($checkExist) >= 1) {
                return [
                    'error_code' => 0,
                    'error_msg'  => '您添加的项目已经存在，不能重复~',
                ];
            }
            $updata = array(
                'daili_dealer_id' => $daili_dealer_id,
                'co_id'           => $co_id,
                'm_id'            => $dl_id,
                'title'           => $this->request->input('title'),
                'is_enable'       => 1,
                'is_set'          => 1,
                'is_default'      => 0,
                'add_time'        => time(),
                'dealer_id'       => $dealer_id,
            );
            if (intval($id) > 0) {
                $e = HgDealerBaoXian::where('daili_dealer_id', $daili_dealer_id)->where('id',
                    $id)->update($updata);//更新，
            } else {
                $arr['id'] = HgDealerBaoXian::insertGetId($updata);
                $arr['bx_id'] = $co_id;
                return json_encode($arr);
            }

        } elseif ($type == 'del-baoxian') {
            $id = $this->request->input('id');
            $e = HgDealerBaoXian::where('m_id', $dl_id)->where('co_id', $id)->delete();//删除保险，
        } elseif ($type == 'addzengpin') {
            $data['zp_id'] = $this->request->input('dl_zp_id');
            $data['dealer_id'] = $this->request->input('dealer_id');
            $data['dl_zp_num'] = $this->request->input('num');
            $data['dl_id'] = $dl_id;
            $data['daili_dealer_id'] = $this->request->input('daili_dealer_id');
            $checkExist = DB::table('hg_dealer_zengpin')
                ->where('zp_id', $data['zp_id'])
                ->where('dealer_id', $data['dealer_id'])
                ->where('dl_id', $data['dl_id'])
                ->where('daili_dealer_id', $data['daili_dealer_id'])
                ->get();
            if ($checkExist->count()) {
                return [
                    'error_code' => '0',
                    'error_msg'  => '您添加的项目已经存在，不能重复~'
                ];
            } else {
                $arr['id'] = DB::table('hg_dealer_zengpin')->insertGetId($data);
                $arr['deaer'] = $data['dealer_id'];
                return json_encode($arr);
            }
        } elseif ($type == 'add-zengpin') {
            $dl_zp_id = $this->request->input('dl_zp_id');
            $id = $this->request->input('id');
            $num = $this->request->input('num');
            if ($dl_zp_id == 0) {//不存在 直接入库
                $data = array('zp_id' => $id, 'dl_zp_num' => $num, 'dl_id' => session('user.member_id'));
                $e = DB::table('hg_dealer_zengpin')->insertGetId($data);
            } else {//存在 更新
                $data = array('zp_id' => $id, 'dl_zp_num' => $num);
                $e = DB::table('hg_dealer_zengpin')->where('dl_zp_id', $dl_zp_id)->where('dl_id',
                    session('user.member_id'))->update($data);
            }
        } elseif ($type == 'del-zengpin') {
            $dl_zp_id = $this->request->input('dl_zp_id');
            $dealer_id = $this->request->input('dealer_id');
            $e = DB::table('hg_dealer_zengpin')
                ->where('dl_zp_id', $dl_zp_id)
                ->where('dealer_id', $dealer_id)
                ->where('dl_id', session('user.member_id'))->delete();
        } elseif ($type == 'del-fees') {
            $dl_zf_id = $this->request->input('dl_zf_id');
            $dealer_id = $this->request->input('dealer_id');
            $e = DB::table('hg_dealer_other_price')
                ->where('id', $dl_zf_id)
                ->where('dealer_id', $dealer_id)
                ->where('daili_id', session('user.member_id'))
                ->delete();
        }
        if (!$e) {
            return [
                'error_code' => 0,
                'error_msg'  => '删除失败',
            ];
        } else {
            return [
                'error_code' => 1,
                'error_msg'  => '删除成功',
            ];
        }
    }


    /**
     * 删除经销商 add by jerry
     * @param 代理经销商id $daili_dealer_id
     * @param 经销商id $dealer_id
     * return
     */
    public function realDeleteDealer($daili_dealer_id, $dealer_id)
    {
        return json_encode(HgDailiDealer::realDeleteDealer($daili_dealer_id, $dealer_id, session('user.member_id')));
    }

    /**
     * 发送邮件验证链接
     * @param $email
     * @return mixed
     */
    public function sendEmail($email, $do = 'send_email_to_change_mobile')
    {
        $data = [
            'email' => $email,
            'time'  => time(),
        ];
        if ($do == 'send_email_to_change_mobile') {
            $data['verify'] = 1;
            $subject = trans('common.verify.email_send_to_change_phone');
            $tpl = '_layout.email_send_to_change_phone';
            $url = url('/dealer/changemobile/verify_email_to_change_phone?data=' . Crypt::encrypt($data));
        } elseif ($do == 'send_email_to_change_email') {
            $data['verify'] = 1;
            $subject = trans('common.verify.email');
            $tpl = '_layout.email_send_to_change_email';
            $url = url('/dealer/changeemail/verify_email_to_change_email?data=' . Crypt::encrypt($data));
        } elseif ($do == 'send_email_to_check_orgin_email') {
            $data['verify'] = 1;
            $subject = trans('common.verify.email');
            $tpl = '_layout.email_send_to_change_email';
            $url = url('/dealer/changeemail/verify_email_to_check_email?data=' . Crypt::encrypt($data));
        } else {
            return false;
        }
        //邮件发送模板
        return Mail::send(
            $tpl,
            [
                'name'    => $email,
                'email'   => $email,
                'url'     => $url,
                'subject' => $subject,
            ],
            function ($m) use ($email, $subject) {
                $m->to($email)->subject('[' . trans('common.www_title') . '] ' . $subject);
            }
        );
    }

    /**
     * 根据品牌地区选择经销商
     * @param $city_id
     * @param $hfbrand
     * @return string
     */
    public function getDealerList($city_id, $hfbrand)
    {
        $list = HgDealer::where('d_shi', $city_id)
            ->where('d_brand_id', $hfbrand)
            ->where('d_is_show', '1')
            ->get();
        if (!empty($list)) {
            $list = $list->toArray();
        } else {
            $list = array();
        }
        return json_encode($list);
    }

}
