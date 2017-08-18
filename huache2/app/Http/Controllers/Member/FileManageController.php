<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\QiNiuImage;
use App\Models\UserExtension;
use App\Http\Requests\UserExtensionRequest;
use Illuminate\Support\Facades\Input;
use App\Models\UserIdcartLog;

class FileManageController extends Controller
{
    protected $user_id;
    protected $upload;
    const IMG_ACTION_TYPE = 'id_cart';
    const NAV_STR = 'myFile';
    const TAB_NAV = 'id_cart';
    public function __construct()
    {
        $this->middleware('auth');
        $this->upload = new QiNiuImage();
    }    
    /**
     * 添加身份认证
     * @return type
     */
    public function AddShowIdCartVerify(){
        $data = ['title'=>'添加身份认证','nav'=>self::NAV_STR];
        $data['tab']   = self::TAB_NAV;
        return view('HomeV2.User.IdCart.idCart-seep1')->with($data);
    }
    /**
     * 查看身份认证页面
     * @param Request $request
     */
    public function showIdCartVerify(Request $request)
    {       
        $userInfo = UserExtension::find($request->user()->id); 
        if(is_null($userInfo)){
            return redirect()->route('auth.addShowIdCart');
        }
        switch($userInfo->is_id_verify){
            case 0://待审核
                $datas = ['tpl'=>'HomeV2.User.IdCart.idCart-seep2','title'=>'实名认证文件待审核'];
                break;
            case 1://成功
                $datas = ['tpl'=>'HomeV2.User.IdCart.idCart-seep-ok','title'=>'实名认证文件审核通过'];
                break;
            default:
                $datas = ['tpl'=>'HomeV2.User.IdCart.idCart-seep3','title'=>'实名认证文件审核驳回'];
        }
        $data = ['title'=>$datas['title'],'user'=>$userInfo,'nav'=>self::NAV_STR];
        $data['tab']   = self::TAB_NAV;
        return view($datas['tpl'])->with($data);
    }
    /**
     * 保存身份验证信息
     * @param Request $request
     */
    public function saveIdCart(UserExtensionRequest $request)
    {
        $userId = $request->user()->id;
        //判断扩展信息是否存在
        $isUserExtension = UserExtension::map(['user_id'=>$userId])->count();
        $type = empty($isUserExtension) ? 'insert' : 'update';
        $where = null;
        if($isUserExtension){
            $where = ['user_id'=>$userId];
            $data['is_id_verify'] = 2;
        }else{
            $data['user_id'] = $userId;
        }

        $nameArr = setUserName(Input::get('real_name'),1);
        $data['last_name'] = $nameArr['0'];
        $data['first_name'] = $nameArr['1'];
        $data['id_cart']    = Input::get('id_cart');
        if($request->hasFile('sc_id_cart_img')){
            $isCart_img = upFile($request->file('sc_id_cart_img'),self::IMG_ACTION_TYPE,$userId);
            if(!$isCart_img){
                return setJsonMsg(0,'图片上传错误！');
            }
            $data['sc_id_cart_img'] = $isCart_img;            
        }
       if($request->hasFile('id_facade_img')){
            $isFacade_img = upFile($request->file('id_facade_img'),self::IMG_ACTION_TYPE,$userId);
            if(!$isFacade_img){
                return setJsonMsg(0,'图片上传错误！');
            }
            $data['id_facade_img'] = $isFacade_img;            
        }
        if($request->hasFile('id_behind_img')) {
            $isBehind_img = upFile($request->file('id_behind_img'),self::IMG_ACTION_TYPE,$userId);
            if (!$isBehind_img) {
                return setJsonMsg(0, '图片上传错误！');
            }            
            $data['id_behind_img'] = $isBehind_img;
        }
        $res = UserExtension::saveData($data,$type,$where);
        if($res){
            UserIdcartLog::saveLog(UserExtension::where(['user_id'=>$userId])->first());
        }
        return ($res) ? setJsonMsg(1,'认证成功！') : setJsonMsg(0,'认证失败！');
    }
}
