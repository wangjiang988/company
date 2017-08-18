<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Com\Hwache\Upload\qiniu;
use App\Models\Common;
use Illuminate\Support\Facades\DB;

class QiNiuImage extends Model
{
    protected $table ='qiniu_images';
    protected $primaryKey = 'img_id';
    protected $fillable = ['user_id','action_type','token','img_url'];
    protected $guarded = ['_token','_url'];
    public $timestamps = false;
    protected $QnUP;
    use Common;
    public function __construct()
    {
        $this->QnUP = new qiniu();
    }
    /**
     * @return bool
     */
    public function getToken($key=null){
        return $this->QnUP ->getUploadTonke($key);
    }

    /**
     * 上传图片
     * @param $filePathName
     * @param $token
     * @param string $action_type
     * @return bool
     */
    public function addImg($filePathName,$token,$action_type='',$user_id=0)
    {
        $uploadName = $this->QnUP -> upload($filePathName,$token);
        if($uploadName){
            $data = [
				'user_id'     => $user_id,
                'action_type' => $action_type,
                'token'       => $token,
                'img_url'     => $uploadName
            ];
            return DB::table($this->table)->insertGetId($data);
        }else{
            return false;
        }
    }

    /** 单纯图片上传，成功，返回图片地址
     * @param $filePathName
     * @param $token
     * @return bool|mixed
     */
    public function upload($filePathName,$token)
    {
        $FileName = $this->QnUP -> upload($filePathName,$token);
        return (!$FileName || is_null($FileName)) ? '' : $FileName;
    }

    public function getUrl($token,$options=null){
        return $this->QnUP -> getImgUrl($token,$options);
    }

    public function delImg($map)
    {
        $tokenStr = self::select(DB::raw('GROUP_CONCAT(`token`) as tokens'))->map($map)->first();
        $tokenArr = explode(',',$tokenStr);
        return $this->QnUP ->delImg($tokenArr);
    }
    
    public static function getIdToUrl($img_id){
        $imgUrl = self::map(['img_id'=>$img_id])->value('img_url');
        return is_null($imgUrl) ? '/themes/images/numid.png' : $imgUrl;
    }
}
