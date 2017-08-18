<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserIdcartLog extends Model
{
    protected $table ='user_idcart_log';

    protected $guarded = [];

    public static function saveLog($post)
    {
        //$log = self::where('user_id',$post->user_id)->first();
        $logData['status']          =  0;
        $logData['sc_id_cart_img']  = (int) $post->sc_id_cart_img;
        $logData['id_facade_img']   = (int) $post->id_facade_img;
        $logData['id_behind_img']   = (int) $post->id_behind_img;
        /*if($log){
            $res = self::where('user_id',$post->user_id)->update($logData);
        }else{*/
            $logData['user_id']         = $post->user_id;
            $logData['real_name']       = $post->last_name.$post->first_name;
            $logData['id_cart']         = $post->id_cart;
            $logData['admin_id']        = 0;
            $logData['admin_name']      = 0;
            $logData['review_time']     = '';
            $logData['file_path']       = '';
            $logData['reason']          = '';
            $logData['remark']          = '';
            $res = self::create($logData);
        //}
        return $res;
    }
}
