<?php
/**
 * 短信功能控制器
 *
 * @package   User
 * @author    李扬(Andy) <php360@qq.com>
 * @link      技安后院 http://www.moqifei.com
 * @copyright 苏州华车网路科技有限公司 http://www.hwache.com
 */
namespace App\Http\Controllers\User;

use Cache;
use App\Core\Contracts\Sms\Sms;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SmsController extends Controller
{
    /**
     * 请求依赖
     * @var Request
     */
    private $request;

    /**
     * 短信接口依赖
     * @var Sms
     */
    private $sms;

    /**
     * 构造函数，初始化内部依赖变量
     * @param Request $request
     * @param Sms $sms
     */
    public function __construct(Request $request, Sms $sms)
    {
        $this->request = $request;
        $this->sms     = $sms;
    }

    /**
     * post提交发送短信验证码
     *
     * @return array|string
     */
    public function postGetCode()
    {
        $mobile = $this->request->input('mobile');

        if ($this->request->ajax()) {
            // 发送手机验证码
            $code = get_rand();
            $result = $this->sms->send(
                $mobile,
                json_encode([
                    'code'    => $code,
                    'product' => '华车',
                ]),
                [
                    'sms_free_sign_name' => 'status',
                    'sms_template_code'  => 'status',
                ]
            );
            if ($result) {
                // 先删除之前的缓存
                Cache::forget($mobile);
                // 缓存新的验证码，有效期10分钟
                Cache::put($mobile, $code, config('sms.sms_time_limit'));

                return [
                    'error_code' => 0,
                    'success'    => true,
                    'error_msg'  => trans('user.reg_mobile_code_ok'),
                ];
            } else {
                // 发送验证码失败
                if (session()->has('mobileCode')) {
                    session()->forget('mobileCode');
                }

                return [
                    'error_code' => 1,
                    'success'    => false,
                    'error_msg'  => trans('user.reg_mobile_code_false'),
                ];
            }
        }

        return '';
    }

}
