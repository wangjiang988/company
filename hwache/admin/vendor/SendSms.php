<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/25
 * Time: 19:31
 */
class SendSms
{
    protected $apiUrl;
    public function __construct()
    {
        $this->apiUrl = API_URL . '/sendTestSms';
    }

    /**
     * SendSms constructor.
     * @param $phone
     * @param string $template_code
     */
    public function sendSms($phone,$template_code='78725077',$order='',$options=null)
    {
        $url = !is_null($options) ? '&'.http_build_query($options) : '';
        $curlUrl = $this->apiUrl."?phone={$phone}&template_code={$template_code}&order={$order}{$url}";
        $result = $this->curl($curlUrl);
        return $result;
    }

    private function curl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        /*curl_setopt($ch, CURLOPT_POST, true); // 啟用POST
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query( $post ));
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3000);*/
        $reponse = curl_exec($ch);
        curl_close($ch);
        return $reponse;
    }
}