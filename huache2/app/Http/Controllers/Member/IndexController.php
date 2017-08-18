<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class IndexController extends Controller
{
    const IMG_ACTION_TYPE = 'user_photo';
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex(Request $request)
    {
        $userInfo = User::getUserHomeInfo($request->user()->id);
        $userInfo->call       = is_null($userInfo->call) ? '客官' : $userInfo->call ;
        $userInfo->last_name  = is_null($userInfo->last_name) ? substr($userInfo->phone,-4,4) : $userInfo->last_name ;
        $userInfo->photo      = is_null($userInfo->photo) ? '' : $userInfo->photo ;
        $userInfo->user_money = is_null($userInfo->user_money) ? '00.00' : $userInfo->user_money ;
        //$userInfo = User::find($user_id)->UserExtension;
        $data = ['user'=>$userInfo,'title'=>'用户中心','nav'=>'index'];
        //用户订单
        $userOrder = User::find($request->user()->id)->UserOrder()->paginate(3);
        if($userOrder->total() >0){
            $data['orders'] = $userOrder->items();
        }
        //用户资金账户
        $userAcconut = User::find($request->user()->id)->UserAccount()->first();
        if(!is_null($userAcconut)){
            $data['account'] = $userAcconut;
        }
        //dd($userOrder->toArray());
        return view('HomeV2.User.index')->with($data);
    }    
    /**
     * 用户资料管理
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getInfo(Request $request)
    {
        $userInfo = User::getUserHomeInfo($request->user()->id);
        //我的银行卡
        //User::$BrankHasOne = true;
        $branks = \App\Models\UserBrank::map(['is_default'=>1,'user_id'=>$request->user()->id])->first();
        $data = ['user'=>$userInfo,'brank'=>$branks,'title'=>'用户中心-个人资料','nav'=>'info'];
        return view('HomeV2.User.info')->with($data);
    }      
    /**
     * 修改用户资料
     * @param type $type
     */
    public function upInfo(Request $request,$type){
        $userId = $request->user()->id;
        if($request->isMethod('post')){
            switch($type){
               case 'photo'://修改头像
                    $this->validate($request, ['photo'=>'required|image']);
                    if($request->hasFile('photo')){
                        $data['photo'] = upFile($request->file('photo'),self::IMG_ACTION_TYPE,$userId);
                        $res = $this->setUserInfo($userId,$data);
                    }
                   break;
               case 'call'://修改称呼
                   $this->validate($request, ['nickName'=>'required|chinese']);
                   $res = $this->setUserInfo($userId,['call'=>$request->input('nickName')]);
                   break;
               case 'address'://修改地址
                    $this->validate($request, ['province'=>'required','city'=>'required','address'=>'required|min:6']);
                    $province = $request->input('province','');
                    $city     = $request->input('city','');
                    $district = $request->input('district','');
                    $address  = $request->input('address','');
                    $addressId = $this->setUserAddress($userId,$province,$city,$district,$address);
                    $res = $this->setUserInfo($userId,['address_id'=>$addressId]);
                   break;
            }            
            return ($res) ? setJsonMsg(1,'设置成功！') : setJsonMsg(0,'设置失败！');
        } 
        dd($type);
    }
    /**
     * 修改用户信息
     * @param type $userId
     * @param type $data
     * @return type
     */
    private function setUserInfo($userId,$data){
        $isUserExtension = \App\Models\UserExtension::map(['user_id'=>$userId])->count();
        $type = empty($isUserExtension) ? 'insert' : 'update';
        $where = null;
        if($isUserExtension){
            $where = ['user_id'=>$userId];
        }else{
            $data['user_id'] = $userId;            
        }        
        return \App\Models\UserExtension::saveData($data,$type,$where);        
    }
    
    private function setUserAddress($userId,$province,$city,$district='',$address=''){
        $isUserExtension = \App\Models\UserAddress::map(['user_id'=>$userId,'is_default'=>1,'activated'=>1])->count();
        $type = empty($isUserExtension) ? 'insert' : 'update';
        $where = null;
        if($isUserExtension){
            $where = ['user_id'=>$userId,'is_default'=>1,'activated'=>1];
        }else{
            $data['user_id'] = $userId;            
        } 
        $data['province'] = $province;
        $data['city']     = $city;
        $data['district'] = $district;
        $data['address']  = $address;
        $res = \App\Models\UserAddress::saveData($data,$type,$where);
        if($res == true){
            if($type == 'update'){                
                $res = \App\Models\UserAddress::map(['user_id'=>$userId,'is_default'=>1,'activated'=>1])->value('address_id');
            }           
        }
        return $res;
    }

    /**
     * 判断实名认证
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkIdCart(Request $request)
    {
        $userInfo = User::getUserHomeInfo($request->user()->id);
        if(is_null($userInfo->is_id_verify) || $userInfo->is_id_verify!=1){
            $data = setJsonMsg(0,'该用户没有实名认证');
        }else{
            $data = setJsonMsg(1,'该用户已实名认证');
        }
        return response()->json($data);
    }
}
