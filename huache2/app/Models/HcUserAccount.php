<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HcUserAccount extends Model
{
    protected $table = 'hc_user_account';

    protected $primaryKey = 'user_id';

    protected $fillable = ['user_id','total_deposit','avaliable_deposit','freeze_deposit','status'];

    public static function getUserAccountById($id)
    {
        return HcUserAccount::find(['user_id'=>$id])->first();
    }
}
