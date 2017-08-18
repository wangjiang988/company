<?php
/**
 * 华车短信专用模块
 *
 * @author 李扬(Andy) <php360@qq.com>
 * @link 技安后院 http://www.moqifei.com
 * @company 苏州华车网络科技有限公司 http://ww.hwache.com
 * @copyright 苏州华车网络科技有限公司版权所有
 */
namespace App\Http\Controllers\Front;

use Cache;
use App\Core\Contracts\Sms\Sms;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class SmsController extends Controller
{
    /**
     * 接收参数
     *
     * @var Request
     */
    private $request;

    /**
     * 短信接口
     *
     * @var Sms
     */
    private $sms;

    /**
     * 初始化
     *
     * SmsController constructor.
     * @param Request $request
     * @param Sms $sms
     */
    public function __construct(Request $request, Sms $sms)
    {
        $this->request = $request;
        $this->sms = $sms;
    }

    /**
     * 普通验证码获取
     *
     * @method GET /sms/code/{mobile}
     * @param $mobile
     * @return array
     */
    public function show($mobile)
    {
        if ($this->request->ajax()) {
            return $this->getCode($mobile);
        }

        return [
            'code' => -1,
            'success' => false,
            'msg' => '系统繁忙，请稍候再试',
        ];
    }

    /**
     * 普通验证码获取
     *
     * @method POST /sms/code
     * @return array
     */
    public function store()
    {
        if ($this->request->ajax()) {
            return $this->getCode($this->request->input('mobile'));
        }

        return [
            'code' => -1,
            'success' => false,
            'msg' => '系统繁忙，请稍候再试',
        ];
    }

    /*********************************以下是私有方法*********************************/

    /**
     * 获取普通短信验证码
     *
     * @param $mobile
     * @return array
     */
    private function getCode($mobile)
    {
        $code = get_rand();

        $sendSuccess = $this->sms->send(
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

        if ($sendSuccess) {
            // 先删除之前的缓存
            Cache::forget($mobile);
            // 缓存新的验证码，有效期10分钟
            Cache::put($mobile, $code, config('sms.sms_time_limit'));

            return [
                'code' => 0,
                'success' => true,
                'msg'  => '已成功发送短信验证码',
            ];
        }

        return [
            'code' => 10001,
            'success' => false,
            'msg' => '短信发送失败',
        ];
    }

}
