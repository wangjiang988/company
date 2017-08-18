<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBankLog extends Model
{
    protected $table='user_bank_log';

    protected $guarded = [];

    /**
     * 添加认证日志
     * @param $post
     * @return mixed
     */
    public static function saveBankLog($post)
    {
        $logData['user_id']       = $post->user_id;
        $logData['bank_id']       = $post->id;
        $logData['type']          = ($post->is_verify > 2) ? 2 : 1;
        $logData['bank_code']     = $post->bank_code;
        $logData['bank_address']  = $post->bank_address;
        $logData['bank_img']      = $post->bank_img;
        $logData['sc_bank_img']   = $post->sc_bank_img;
        $logData['status']        = 0;
        $logData['admin_id']      = 0;
        $logData['admin_name']    = '';
        $logData['file_path']     = '';
        $logData['reason']        = '';
        $logData['remark']        = '';
        return self::create($logData);
    }
}
