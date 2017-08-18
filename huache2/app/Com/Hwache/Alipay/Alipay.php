<?php namespace App\Com\Hwache\Alipay;
/**
 * 支付宝支付模块
 *
 * @author 技安 php360@qq.com
 * @copyright 苏州华车网络科技有限公司
 */

use App\Com\Hwache\Alipay\Pc\Lib\AlipaySubmit;
use App\Com\Hwache\Alipay\Pc\Lib\AlipayNotify;

class Alipay
{
    // PID
    private $pid = null;
    // Email
    private $email = null;
    // KEY
    private $key = null;
    // URL
    private $url = null;

    private $alipay_config;

    /**
     * 初始化支付宝参数
     * @internal param $pid
     * @internal param $email
     * @internal param $key
     * @internal param $url
     */
    public function __construct()
    {
        $this->pid = config('pay.alipay.pid');
        $this->email = config('pay.alipay.email');
        $this->key = config('pay.alipay.key');
        $this->url = config('pay.alipay.url');
        $this->alipay_config = [
            'partner'       => $this->pid,
            'seller_email'  => $this->email,
            'key'           => $this->key,
            'sign_type'     => 'MD5',
            'input_charset' => 'utf-8',
            'cacert'        => __DIR__.'\\cacert.pem',
            'transport'     => 'http',
        ];
    }

    public function paramPostData()
    {
        //支付类型
        $payment_type = "1";
        //必填，不能修改
        //服务器异步通知页面路径
        $notify_url = $this->url.'/notify';
        //需http://格式的完整路径，不能加?id=123这类自定义参数

        //页面跳转同步通知页面路径
        $return_url = $this->url.'/return';
        //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/

        //商户订单号
        $out_trade_no = session('order.order_num');
        //商户网站订单系统中唯一订单号，必填

        //订单名称
        $subject = htmlspecialchars_decode(session('order.order_name'));
        //必填

        //付款金额
        $total_fee = session('order.money');
        //必填

        //订单描述

        $body = '';
        //商品展示地址
        $show_url = '';
        //需以http://开头的完整路径，例如：http://www.商户网址.com/myorder.html

        //防钓鱼时间戳
        $anti_phishing_key = "";
        //若要使用请调用类文件submit中的query_timestamp函数

        //客户端的IP地址
        $exter_invoke_ip = "";
        //非局域网的外网IP地址，如：221.0.0.1


        $parameter = array(
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
        );

        //建立请求
        $alipaySubmit = new AlipaySubmit($this->alipay_config);//dd($alipaySubmit);

        $html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
        echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
        echo '<html><head>';
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
        echo '<title>支付宝即时到账交易接口接口</title>';
        echo '</head><body>';
        echo $html_text;
        echo '</body></html>';
    }

    public function getResult()
    {
        //计算得出通知验证结果
        $alipayNotify = new AlipayNotify($this->alipay_config);
        $verify_result = $alipayNotify->verifyReturn();
        if($verify_result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代码

            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

            //商户订单号

            $out_trade_no = $_GET['out_trade_no'];

            //支付宝交易号

            $trade_no = $_GET['trade_no'];

            //交易状态
            $trade_status = $_GET['trade_status'];


            if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //如果有做过处理，不执行商户的业务程序
            }
            else {
                echo "trade_status=".$_GET['trade_status'];
            }

            //echo "验证成功<br />";
            session(['order.trade_no' => $_GET['trade_no']]);
            return true;

            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        }
        else {
            //验证失败
            //如要调试，请看alipay_notify.php页面的verifyReturn函数
            //echo "验证失败";
            return false;
        }
    }





    public function __set($name, $value)
    {
        $this->{$name} = $value;
    }

    public function __get($name)
    {
        return $this->{$name};
    }
}