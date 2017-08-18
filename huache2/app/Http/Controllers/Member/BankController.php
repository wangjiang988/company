<?php

namespace App\Http\Controllers\Member;

use App\Models\QiNiuImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserBrank;
use App\Models\UserBankLog;
use App\Http\Requests\BrankRequest;

class BankController extends Controller
{
    //
    protected $brank;
    protected $upload;
    const IMG_ACTION_TYPE = 'bank_img';
    const NAV_SELECTED = 'bank';
    const TAB_NAV      = 'bank';

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('user.is_idcart')->except('getBankList');
        $this->brank = new UserBrank();
        $this->upload = new QiNiuImage();
    }
    
    public function getIndex(Request $request)
    {
        $userBrank = $this->getUserBank($request->user()->id);
        if(!is_null($userBrank)){
            if($userBrank->is_verify >2)
                return redirect()->route('bank.updateShow',['id'=>$userBrank->id]);
            else
               return redirect()->route('bank.addShow',['id'=>$userBrank->id]); 
        }
        //获取用户的银行卡信息        
        $region = \App\Models\Area::InputSelect();
        $userInfo = \App\User::getUserHomeInfo($request->user()->id);
        $data = ['region'=>$region,'title'=>'银行卡管理','nav'=>self::NAV_SELECTED,'user'=>$userInfo];
        $data['tab']   = self::TAB_NAV;
        return view('HomeV2.User.Brank.index')->with($data);
    }
    /**
     * 修改银行卡
     * @param type $id
     * @return type
     */
    public function getEdit(Request $request,$id){
        $brankId = (int) $id;
        $userBank = $this->getUserBank($request->user()->id,$brankId);
        if(is_null($userBank)){
            return redirect()->route('user.bank');
        }
        $region = \App\Models\Area::InputSelect();
        $userInfo = \App\User::getUserHomeInfo($request->user()->id);
        $data = ['region'=>$region,'title'=>'银行卡管理','nav'=>self::NAV_SELECTED,'user'=>$userInfo,'bank'=>$userBank];
        $data['tab']   = self::TAB_NAV;
        return view('HomeV2.User.Brank.edit')->with($data);
    }
    /**
     * 查看银行账号新增状态
     * @param type $id
     * @return type
     */
    public function getShow(Request $request,$id){
        $userBank = $this->getUserBank($request->user()->id,$id);
        $userInfo = \App\User::getUserHomeInfo($request->user()->id);
        if(!is_null($userBank)){
            switch($userBank->is_verify){
                case 0://待审核
                    $tpl = 'HomeV2.User.Brank.seep1';
                    break;
                case 1://审核通过
                    $tpl = 'HomeV2.User.Brank.seep2';
                    break;
                case 2://审核驳回
                    $tpl = 'HomeV2.User.Brank.seep3';
                    break; 
                default:
                  return redirect()->route('bank.updateShow',['id'=>$id]);  
            }
        }else{
            return redirect()->route('user.bank');
        }
        $data = ['title'=>'新增银行卡查看状态','nav'=>self::NAV_SELECTED,'bank'=>$userBank,'user'=>$userInfo];
        $data['tab']   = self::TAB_NAV;
        return view($tpl)->with($data);
    }
    /**
     * 查看银行账号修改状态
     * @param type $id
     * @return type
     */
    public function getUpdateShow(Request $request,$id){
        $userBank = $this->getUserBank($request->user()->id,$id);
        $userInfo = \App\User::getUserHomeInfo($request->user()->id);
        if(!is_null($userBank)){
            switch($userBank->is_verify){
                case 3://修改待审核                   
                    $tpl = 'HomeV2.User.Brank.seep5';
                    break;
                case 4://修改审核驳回
                    $tpl = 'HomeV2.User.Brank.seep6';
                    break; 
                default:
                  return redirect()->route('bank.addShow',['id'=>$id]);
            }
        }else{
            return redirect()->route('user.bank');
        }
        $data = ['title'=>'修改银行卡查看状态','nav'=>self::NAV_SELECTED,'bank'=>$userBank,'user'=>$userInfo];
        $data['tab']   = self::TAB_NAV;
        return view($tpl)->with($data);
    }
    /**
     * 保存银行卡信息
     */
    public function postSave(\App\Http\Requests\BrankRequest $request)
    {
        //$this->isUserVerify($request->user()->id,$request->ajax());
        if($request->isMethod('post')){
            $id = $request->input('id',0);
            $userBrank = $this->getUserBank($request->user()->id,$id);
            $is_verify = empty($id) ? 0 : 3;
            $data = [
                'user_id'       => $request->user()->id,
                'bank_code'     => $request->input('bank_code',''),
                'bank_name'     => $request->input('bank_name',''),
                'province'      => $request->input('province',''),
                'city'          => $request->input('city',''),
                'district'      => $request->input('district',''),
                'bank_address'  => $request->input('bank_address',''),
                'is_default'    => $request->input('is_default',1),
                'bank_img'      => is_null($userBrank) ? 0 : $userBrank->bank_img,
                'sc_bank_img'   => is_null($userBrank) ? 0 : $userBrank->sc_bank_img,
                'is_verify'     => $is_verify
            ];
            if($request->hasFile('bank_img')){
                $data['bank_img'] = upFile($request->file('bank_img'),self::IMG_ACTION_TYPE,$request->user()->id);
            }
            if($request->hasFile('sc_bank_img')){
                $data['sc_bank_img'] = upFile($request->file('sc_bank_img'),self::IMG_ACTION_TYPE,$request->user()->id);
            }
            $res = $this->brank ->saveData($data,$id);
            if($res){
                $insertId    = empty($id) ? $res : $id;
                $push['url'] = empty($id) ? route('bank.addShow',['id'=>$insertId]) : route('bank.updateShow',['id'=>$insertId]);
                //写入待审核日志
                UserBankLog::saveBankLog(UserBrank::where(['user_id'=>$request->user()->id])->first());
                return setJsonMsg(1,'成功',$push);
            }else{
                return setJsonMsg(0,'失败');
            }           
        }
    }

    private function getUserBank($user_id,$id=null){
       $map = !is_null($id) ? ['is_default'=>1,'id'=>$id] : $id;
       $bank = \App\User::find($user_id)->UserBank(true,$map);
       return $bank->first();
    }

    /**
     * 获取用户的银行卡列表
     * @param Request $request
     * @return array
     */
    public function getBankList(Request $request)
    {
        if($request->ajax()){
            if($request->has('bank_code')){
                $bank_code = $request->get('bank_code','');
                $result = \App\User::find($request->user()->id)->UserBank(true,['bank_code'=>$bank_code])
                    ->first();
                if($result)  $result  =  $result->toArray();
            }else{
                $result = \App\User::find($request->user()->id)->UserBank(false)->pluck('bank_code')->toArray();
            }
            return !is_null($result) ? setJsonMsg(1,'成功',['data'=>$result]) : setJsonMsg(0,'没有数据');
        }
    }
}
