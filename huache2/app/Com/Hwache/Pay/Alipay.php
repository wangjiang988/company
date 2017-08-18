<?php
/**
 * 支付宝支付模块
 *
 * @author 李扬(Andy) <php360@qq.com>
 * @link 技安后院 http://www.moqifei.com
 * @copyright 苏州华车网络科技有限公司 http://www.hwache.com
 */
namespace App\Com\Hwache\Pay;

use App\Com\Hwache\Pay\Alipay\Pc\Lib\AlipayNotify;
use App\Com\Hwache\Pay\Alipay\Pc\Lib\AlipaySubmit;

class Alipay
{
    // PID
    private $pid = null;
    // Email
    private $email = null;
    // KEY
    private $key = null;
    // Alipay config
    private $alipay_config;

    /**
     * 初始化支付宝参数
     */
    public function __construct()
    {
        $this->pid   = config('pay.alipay.pid');
        $this->email = config('pay.alipay.email');
        $this->key   = config('pay.alipay.key');
        $this->alipay_config = [
            'partner'       => $this->pid,
            'seller_email'  => $this->email,
            'key'           => $this->key,
            'sign_type'     => 'MD5',
            'input_charset' => 'utf-8',
            'cacert'        => __DIR__.'/cacert.pem',
            'transport'     => 'http',
        ];
    }

    /**
     * 支付
     *
     * @param string $orderNum 订单号
     * @param string $orderName 订单名称（商品名称）
     * @param float $money 金额
     * @return mixed
     */
    public function pay($orderNum, $orderName, $money)
    {
        if (env('APP_ENV') == 'local') {
            $url = [
                "buyer_email" => "test@hwache.com", // 买家支付宝账号
                "buyer_id" => "2088123456789012", // 买家支付宝账户号
                "exterface" => "create_direct_pay_by_user", // 接口名称
                "is_success" => "T", // 成功标识.表示接口调用是否成功,并不表明业务处理结果
                "notify_id" => "RqPnCoPT3K9%2Fvwbh3InVamgCCiYPJlb%2Bm2rjS1vumBQmmm7%2FvDY0z1lp0uTS1W2uIRF%2F", // 通知校验ID
                "notify_time" => date('Y-m-d H:i:s'), // 通知时间(支付宝时间) 格式为 yyyy-MM-dd HH:mm:ss
                "notify_type" => "trade_status_sync", // 通知类型
                "out_trade_no" => $orderNum, // 商户网站唯一订单号
                "payment_type" => "1", // 支付类型
                "seller_email" => "hwache@hwache.com", // 卖家支付宝账号
                "seller_id" => "2088987654321012", // 卖家支付宝账户号
                "subject" => htmlspecialchars_decode($orderName), // 商品名称
                "total_fee" => $money, // 交易金额
                "trade_no" => "2015101021001004960016133586", // 支付宝交易号
                "trade_status" => "TRADE_SUCCESS", // 交易状态:TRADE_FINISHED(普通即时到账的交易成功状态),TRADE_SUCCESS(开通了高级即时到账或机票分销产品后的交易成功状态
                "sign" => "a4411452cc44a381a4221f0e1cefb683", // 签名
                "sign_type" => "MD5", // 签名方式
            ];
            $url = route('user.money.return', ['alipay']).'?'.http_build_query($url);

            return redirect($url);
        }

        //支付类型
        $payment_type = '1';
        //必填
        //服务器异步通知页面路径,需http://格式的完整路径，不能加?id=123这类自定义参数
        $notify_url = route('user.money.notify', ['alipay']);

        //必填
        //页面跳转同步通知页面路径,需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/
        $return_url = route('user.money.return', ['alipay']);

        //商户网站订单系统中唯一订单号，必填
        $out_trade_no = $orderNum;

        // 必填
        //订单名称
        $subject = htmlspecialchars_decode($orderName);

        // 必填
        //付款金额
        $total_fee = $money;

        //订单描述
        $body = '';
        //商品展示地址,需以http://开头的完整路径，例如：http://www.商户网址.com/myorder.html
        $show_url = '';

        // 防钓鱼时间戳,若要使用请调用类文件submit中的query_timestamp函数
        $anti_phishing_key = '';

        // 客户端的IP地址,非局域网的外网IP地址，如：221.0.0.1
        $exter_invoke_ip = "";

        $parameter = [
            "service"           => "create_direct_pay_by_user",
            "partner"           => trim($this->alipay_config['partner']),
            "seller_email"      => trim($this->alipay_config['seller_email']),
            "payment_type"      => $payment_type,
            "notify_url"        => $notify_url,
            "return_url"        => $return_url,
            "out_trade_no"      => $out_trade_no,
            "subject"           => $subject,
            "total_fee"         => $total_fee,
            "body"              => $body,
            "show_url"          => $show_url,
            "anti_phishing_key" => $anti_phishing_key,
            "exter_invoke_ip"   => $exter_invoke_ip,
            "_input_charset"    => trim(strtolower($this->alipay_config['input_charset']))
        ];

        //建立请求
        $alipaySubmit = new AlipaySubmit($this->alipay_config);

        $html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
        echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
        echo '<html><head>';
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
        echo '<title>支付宝即时到账交易接口接口</title>';
        echo '</head><body>';
        echo $html_text;
        echo '</body></html>';
    }

    /**
     * 获取同步支付通知
     *
     * @return array|bool mixed
     */
    public function getResult()
    {
        if (env('APP_ENV') == 'local') {
            return [
                'success' => true,
                'msg'     => 'success',
                'data'    => [
                    'serial_id'     => $_GET['out_trade_no'],
                    'money'         => $_GET['total_fee'],
                    'pay_trade_id'  => $_GET['trade_no'],
                    'pay_status'    => 1,
                    'pay_time'      => $_GET['notify_time'],
                ],
            ];
        }

        //计算得出通知验证结果
        $alipayNotify  = new AlipayNotify($this->alipay_config);
        $verify_result = $alipayNotify->verifyReturn();
        if ($verify_result) {//验证成功
            return [
                'success' => true,
                'msg'     => 'success',
                'data'    => [
                    'serial_id'     => $_GET['out_trade_no'],
                    'money'         => $_GET['total_fee'],
                    'pay_trade_id'  => $_GET['trade_no'],
                    'pay_status'    => 1,
                    'pay_time'      => $_GET['notify_time'],
                ],
            ];
        } else {
            return [
                'success' => false,
                'msg'     => 'fail',
            ];
        }
    }

    /**
     * 获取异步支付通知
     *
     * @return mixed
     */
    public function getAsynchronyResult()
    {
        // 计算得出通知验证结果
        $alipayNotify = new AlipayNotify($this->alipay_config);
        $verify_result = $alipayNotify->verifyNotify();

        if ($verify_result) { // 验证成功
            return [
                'success' => true,
                'msg'     => 'success',
                'data'    => [
                    'serial_id'     => $_POST['out_trade_no'],
                    'money'         => $_POST['total_fee'],
                    'pay_trade_id'  => $_POST['trade_no'],
                    'pay_status'    => 1,
                    'pay_time'      => $_POST['notify_time'],
                ],
            ];
        } else {
            return [
                'success'   => false,
                'msg'       => 'fail',
            ];
        }
    }

}
