<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

/**
 * 操作session数据
 * Class setSession
 * @package App\Http\Requests
 */
class SetSession extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * 删除次数冻结
     * @param Request $request
     * @return array|null
     */
    public function delData($sessionName)
    {
        $this->session()->forget($sessionName);
        $this->session()->save();
    }

    /**
     * 设置session 数据
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $sessionName
     * @param $data
     */
    public function setData($sessionName,$data)
    {
        $this->session()->put($sessionName,$data);
        $this->session()->save();
    }

    /**
     * 获取session数据
     * @param $sessionName
     * @return mixed
     */
    public function getData($sessionName)
    {
        return $this->session()->get($sessionName);//session($sessionName);//
    }

    /**
     * @return string 当天结束时间
     */
    public function getEndDay()
    {
        return endToDayTime();
    }
    /**
     * 发送次数冻结
     * @param Request $request
     * @return array|null
     */
    public function checkSessionFreeze($sessionName,$max,$endTime,$errorCode=null){
        if(!$this->session()->has($sessionName)){
            $this->setData($sessionName,['endtime'=>$endTime,'click'=>0]);
        }
        $lastAction = $this->getData($sessionName);
        if(strtotime(Carbon::now()->toDateTimeString()) > strtotime($lastAction['endtime'])){
            $this->delData($sessionName);
        }else{
            $this->setData($sessionName,['endtime'=>$lastAction['endtime'],'click'=>$lastAction['click']+1]);
        }
        return $this->setFreezeData($sessionName,$max,$errorCode);
    }

    private function setFreezeData($sessionName,$max,$errorCode=null)
    {
        $result = null;
        $lastAction = $this->getData($sessionName);
        if($max <= $lastAction['click']){
            return setJsonMsg(0,'您今天的验证次数已超过上限！',['count'=>$max],$errorCode);exit;
        }
        return $result;
    }
}
