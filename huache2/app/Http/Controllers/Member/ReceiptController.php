<?php
/**
 * 我的发票
 */
namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReceiptController extends Controller
{
    const NAV_TAB = 'Receipt';

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * 列表
     */
    public function getIndex()
    {
      //  dd('没有UI，从哪来回哪去！');
        $data['title'] = '我的发票';
        $data['nav']   = self::NAV_TAB;
        $data['active'] = 'fapiao';
        return view('Temp.financial_temp')->with($data);
      //  return view('HomeV2.User.Receipt.index')->with($data);
    }
}
