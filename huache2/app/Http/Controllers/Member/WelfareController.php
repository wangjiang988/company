<?php
/**
 * 我的福利
 */
namespace App\Http\Controllers\Member;

use App\Models\HcVoucher;
use App\Models\HgGoodsClass;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WelfareController extends Controller
{
    const NAV_TAB = 'Welfare';

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * 列表
     */
    public function getIndex(Request $request)
    {
        $data['title'] = '我的福利';
        $data['nav']   = self::NAV_TAB;

        return view('HomeV2.User.Welfare.index')->with($data);
    }

    /**
     *  获取代金券方法
     */
    public function getVouchersList(Request $request)
    {
        $this->validate($request,[
            'vouchersStatus' => 'required|integer'
        ]);
        $hc_voucher   =  new HcVoucher();
        $status       =  $request->vouchersStatus;
        $user_id  = $request->user()->id;
        //获取所有可用代金券
        $where = [
            'status'=>$status,
        ];
        if(isset($request->volumeTypeId))  $where['group_type_id'] = $request->volumeTypeId;
        $list         =  $hc_voucher->get_vouchers_by_user_id($user_id,$where);
        $total_page   = (int)ceil($list->total()/$list->perPage());
        return response()->json(['list'=> $list,'total_page'=>$total_page]);
    }


}
