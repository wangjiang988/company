<?php
/**
 * 短信接口
 *
 * @author 李扬(Andy) <php360@qq.com>
 * @link   技安后院 http://www.moqifei.com
 */
namespace App\Core\Contracts\Sms;

interface Sms
{
    /**
     * 设置短信接口参数
     * @param array $config 参数配置数组，比如AppKey，AppSecret等等
     * @return mixed
     */
    public function setConfig(array $config);

    /**
     * 短信发送
     * @param string $mobile 发送短信的号码
     * @param string|array $text 发送的短信内容
     * @param array $param 其他的短信参数
     * @return bool
     */
    public function send($mobile, $text, array $param = array());

    /**
     * 查短信发送记录
     * @param string $mobile 需要查询的手机号
     * @param string $startTime 短信发送开始时间
     * @param string $endTime 短信发送结束时间
     * @param int $pageNum 页码，从1开始
     * @param int $pageSize 每页个数，默认50，一般各短信接口最大限制是100
     * @return mixed
     */
    public function query($mobile, $startTime, $endTime, $pageNum = 1, $pageSize = 20);
}

