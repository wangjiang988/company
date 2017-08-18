<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Larastarscn\AliDaYu\Facades\AliDaYu;
use Illuminate\Support\Facades\DB;

class SendSmsLog extends Model
{
    protected $table    = 'send_sms_log';
    //protected $fillable = ['user_id','action_id','type','phone','code','content','send_time','validity_time','desc','status'];
    protected $guarded = [];
    /**
     * @param $data
     * @param string $type
     * @param null $where
     * @return mixed
     */
    public function saveData($data,$type='insert',$where=null){
        switch($type){
            case 'insert':
                return self::create($data);
                break;
            case 'update':
                return self::where($where[0],$where[1],$where[2])->update($data);
                break;
        }
    }

    public static function getCodeOption()
    {
        return ['code' => get_rand(1, 6)];
    }
    /**
     * 获取验证码
     * @return array
     */
    public static function getOption(Request $request)
    {
        $acceptsArray = ['code','time','mail','paytype','money','bankno','password','user','order','express','express_no','tel','account'];
        $request->accepts($acceptsArray);
        $option = [];
        foreach($acceptsArray as $accepts){
            if($request->has($accepts)) {
                if ($accepts == 'code') {
                    $isCode = $request->get($accepts);
                    if(!is_null($isCode) && $isCode >0){
                        $option['code'] = get_rand(1, 6);
                    }
                } else {
                    $option[$accepts] = $request->get($accepts);
                }
            }
        }
        return count($option) >0 ? $option : null;
    }
    /**
     * 判断当天手机验证的次数
     * @param $phone
     * @param $type
     * @param $hour （大于0时：某小时内的发送次数，否则是当天的发送次数）
     * @return bool
     */
    public function isDayCheckMsg($phone,$template_code=null,$hour=0){
        if($hour >0)
            $where = "created_at >= DATE_SUB(NOW(),INTERVAL {$hour} HOUR)";
        else
            $where = "FROM_UNIXTIME(send_time,'%Y-%m-%d')=CURDATE()";
        $model = self::where('phone','=',$phone)
            ->where(function ($query) use ($template_code) {
                if ($template_code) {
                    $query->where('sms_template_code', $template_code);
                }
            })
            ->where('status','=',1)
            ->whereRaw($where);
        $isLog = $model->first();
        return ($isLog) ? $model->count() : 0;
    }
    /** 短信验证
     * @param $phone
     * @param $type
     * @param $day
     * @param $code
     * @return mixed
     */
    public function getValidationSms($phone,$template_code){
        return self::where('phone','=',$phone)
            ->where(function ($query) use ($template_code) {
                if ($template_code) {
                    $query->where('sms_template_code', $template_code);
                }
            })->where('status', '=', 1)
            ->where('is_check',0)
            ->whereRaw("FROM_UNIXTIME(send_time,'%Y-%m-%d')=CURDATE()")
            ->whereRaw("(send_time+validity_time) >= UNIX_TIMESTAMP()")
            ->orderBy('id','desc')
            ->limit(1)
            ->first();
    }

    /**
     * 验证短信
     * @param $phone
     * @param $type
     * @param $code
     * @return bool
     */
    public function VerifySms($phone, $template_code, $code)
    {
        $smsLog = $this->getValidationSms($phone, $template_code, $code);
        if ($smsLog && intval($smsLog->code == $code)) {
            //更新短信验证状态
            $this->saveData(['is_check' => 1], 'update', ['id', '=', $smsLog->id]);
            return true;
        }

        return false;
    }

    /**
     * 判断当前手机是否验证过
     * @param $phone
     * @param string $type
     * @return int
     */
    public static function isVerifySms($phone,$template_code){
        $day   = date('Y-m-d',time());
        $result = self::where('phone','=',$phone)
            ->where('sms_template_code',$template_code)
            ->where('status','=',1)
            ->whereRaw("FROM_UNIXTIME(send_time,'%Y-%m-%d')='{$day}'")
            ->whereRaw("(send_time+validity_time) >= UNIX_TIMESTAMP() and is_check=1")
            ->orderBy('id','desc')
            ->limit(1)
            ->count(DB::raw('id'));
        return intval($result);
    }
    /**
     * @param $phone  发送短信，并记录短信
     * @param string $template_code
     * @param null $_code
     * @param int $action_id
     * @param int $validity_time
     * @return array|bool
     */
    public function sendSms($phone, $msg_code=null, $option=null , $action_id=0){
        if(!$msg_code) return false;
        $template  =   getSmsTypeContent_v2($msg_code,$option);
        //查看模板是否存在
        if(!$template){
            return false;
        }
        if(env('APP_ENV') == 'local' || in_array($phone, config('alidayu.white_list'))){//本地环境 或白名单不发送短信
            $result = true;
        }else{
            $result   = $this->sendAlidayuSms($phone, $template, $option);
        }
        $code = !isset($option['code']) ? '' : $option['code'];
        if ($result) { //发送成功
            //写入日志
            $this->insertLog($phone, $template, $code , env('SMS_VALIDITY_TIME'), $action_id);
        } else {//发送失败
            //写入日志
            $this->insertLog($phone,$template , $code ,env('SMS_VALIDITY_TIME'),$action_id,false);
        }
        return $result;
    }
    /**
     * 写入数据
     * @param type $phone
     * @param type $type
     * @param type $code
     * @param type $content
     * @param type $validity_time
     * @param type $action_id
     * @param type $isSend
     * @return type
     */
    private function insertLog($phone,$template, $code = '',$validity_time=60,$action_id,$isSend=true){
        //写入日志
        $data = [
            'user_id'       => 0,//(int) User::getPhoneToId($phone),
            'action_id'     => $action_id,
            'type'          => '0',
            'sms_template_code'  => $template->template_code,
            'phone'         => $phone,
            'code'          => $code ,
            'content'       =>  $template->content,
            'send_time'     => time(),
            'validity_time' => $validity_time,
            'desc'          => ($isSend) ? '发送成功' : '发送失败',
            'status'        => intval($isSend)
        ];
        return $this->saveData($data);
    }
    /**
     * 调用发送短信接口 第二版本
     * @param type $phone
     * @param type $type
     * @param type $content
     * @return boolean
     */
    private function sendAlidayuSms($phone,$template,$option=[]){
        $data = [
            //'extend'             => 'hwache',
            'sms_type'           => 'normal',
            'sms_free_sign_name' => '华车',
            'sms_param'          => json_encode($option),
            'rec_num'            => $phone,
            'sms_template_code'  => $template->sms_template_code
        ];
        $response = AliDaYu::driver('sms')->send($data);
        $sendResult = $response->getBody()->getContents();
        $resultArray = \GuzzleHttp\json_decode($sendResult,true);

        if(!array_key_exists('alibaba_aliqin_fc_sms_num_send_response',$resultArray)) {
            return false;
        }
        $object = (object) $resultArray['alibaba_aliqin_fc_sms_num_send_response'];
        if (isset($object->result)) {
            return $object->result['success'];
        }
        return false;
    }

    /**
     * 短信验证码 ：使用同一个签名，对同一个手机号码发送短信验证码，1条/分钟，5条/小时，10条/天。一个手机号码通过阿里大于平台只能收到40条/天。
     * 短信通知： 使用同一个签名和同一个短信模板ID，对同一个手机号码发送短信通知，支持50条/日（指自然日）
     * @param $phone
     * @param $template_code
     * @param $iscode
     * @return bool
     */
    public function checkTodayTotalSms($phone,$iscode=0,$template_code=null)
    {
        $max = ($iscode) ? 200 : 250;//(5个模板每个限制40/50)
        $hourMax = 25;//(5个模板每个限制5条)
        $dayMax  = 50;//(5个模板每个限制10条)
        $sendTotal = $this->isDayCheckMsg($phone);
        //手机号码每天发送限制
        if($sendTotal >= $max){
            return setJsonMsg(0,'短信超出每天发送限制'.$max,['count'=>$sendTotal],'4001');
        }
        if($iscode){
            //每小时限制
            $sendHourTotal = $this->isDayCheckMsg($phone, $template_code,1);
            if($sendHourTotal >= $hourMax){
                return setJsonMsg(0,'该短信模板，短信发送超出每小时限制：5条',['count'=>$sendHourTotal],'4002');
            }else{
                $sendDayTotal = $this->isDayCheckMsg($phone, $template_code);
                if($sendDayTotal >= $dayMax){
                    return setJsonMsg(0,'该短信模板，短信发送超出每天限制：10条',['count'=>$sendDayTotal],'4003');
                }
            }
        }
        return null;
    }
}
