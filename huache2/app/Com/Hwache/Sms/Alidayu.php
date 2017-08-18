<?php
/**
 * 阿里大鱼短信接口
 */
namespace App\Com\Hwache\Sms;

use App\Core\Contracts\Sms\Sms;
/**
 * SDK工作目录
 * 存放日志，TOP缓存数据
 */
define("TOP_SDK_WORK_DIR", storage_path().'/logs/');

/**
 * 是否处于开发模式
 * 在你自己电脑上开发程序的时候千万不要设为false，以免缓存造成你的代码修改了不生效
 * 部署到生产环境正式运营后，如果性能压力大，可以把此常量设定为false，能提高运行速度（对应的代价就是你下次升级程序时要清一下缓存）
 */
define("TOP_SDK_DEV_MODE", config('app.debug'));

class Alidayu implements Sms
{
    private $appKey;

    private $appSecret;

    public function __construct()
    {
        $this->appKey    = config('sms.alidayu.app_key');
        $this->appSecret = config('sms.alidayu.app_secret');
    }

    /**
     * 设置短信接口参数
     * @param array $config 参数配置数组，比如AppKey，AppSecret等等
     * @return mixed
     */
    public function setConfig(array $config)
    {
        // TODO: Implement setConfig() method.
    }

    /**
     * 短信发送
     * @param string $mobile 发送短信的号码
     * @param string|array $text 发送的短信内容
     * @param array $param 其他的短信参数
     * @return mixed
     */
    public function send($mobile, $text, array $param = array())
    {
        require('Alidayu/TopSdk.php');

        $c            = new \TopClient;
        $c->appkey    = $this->appKey;
        $c->secretKey = $this->appSecret;
        $c->format    = 'json';

        $req = new \AlibabaAliqinFcSmsNumSendRequest;
        if (isset($param['extend'])) {
            $req->setExtend($param['extend']);
        }
        $req->setSmsType('normal');
        $req->setSmsFreeSignName(config('sms.alidayu.sign.'.$param['sms_free_sign_name']));
        $req->setSmsParam($text);
        $req->setRecNum($mobile);
        $req->setSmsTemplateCode(config('sms.alidayu.template.'.$param['sms_template_code']));

        $result = $c->execute($req);
        if (isset($result->result)) {
            return $result->result->success;
        }

        \Log::error('短信发送失败', (array) $result);
        return false;
    }

    /**
     * 查短信发送记录
     * @param string $mobile 需要查询的手机号
     * @param string $startTime 短信发送开始时间
     * @param string $endTime 短信发送结束时间
     * @param int $pageNum 页码，从1开始
     * @param int $pageSize 每页个数，默认50，一般各短信接口最大限制是100
     * @return mixed
     */
    public function query($mobile, $startTime, $endTime, $pageNum = 1, $pageSize = 20)
    {
        // TODO: Implement query() method.
    }
}

