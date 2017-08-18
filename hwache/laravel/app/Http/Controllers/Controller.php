<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    /**
     * 成功跳转方法
     * @param $msg 显示信息
     * @param null $url 跳转的url，为空则返回原页面
     * @param int $time 默认停留时间
     * @return mixed
     */
    public function success($msg, $url = null, $time = 3)
    {
        if (empty($url)) {
            $url = session('_previous.url') ? : $_SERVER['HTTP_REFERER'];
        }

        return $this->jumpUrl(true, $msg, $url, $time);
    }

    /**
     * 失败跳转方法
     * @param $msg 显示信息
     * @param null $url 跳转的url，为空则返回原页面
     * @param int $time 默认停留时间
     * @return mixed
     */
    public function error($msg, $url = null, $time = 3)
    {
        if (empty($url)) {
            $url = session('_previous.url') ? : $_SERVER['HTTP_REFERER'];
        }

        return $this->jumpUrl(false, $msg, $url, $time);
    }

    /**
     * 失败跳转方法
     * @param $success 页面状态
     * @param $msg 显示信息
     * @param null $url 跳转的url，为空则返回原页面
     * @param int $time 默认停留时间
     * @return mixed
     */
    private function jumpUrl($success, $msg, $url, $time)
    {
        return view('jump', [
            'success' => $success,
            'msg' => $msg,
            'url' => $url,
            'time' => $time,
        ]);
    }

}
