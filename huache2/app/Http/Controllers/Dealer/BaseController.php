<?php
/**
 * Created by PhpStorm.
 * Date: 2016/7/22
 * Time: 15:41
 */

namespace App\Http\Controllers\Dealer;

use App\Models\HgDailiDealer;
use App\Models\HgOrder;
use Illuminate\Contracts\View\View;
use App\Models\HgBaojia;


class BaseController extends Controller
{
    protected $data;
    protected $baojiaCount;
    protected $orders;

    public function __construct()
    {
        $dl_id = session('user.member_id');
        $this->data = HgDailiDealer::getDealer($dl_id);
        //報價統計
        $this->baojiaCount = HgBaojia::getBaojiaCount($dl_id);
        $orders = app(HgOrder::class)->getCount($dl_id);
        $this->orders = $orders;

    }


    public function compose(View $view)
    {
        $data['common_info'] = $this->data;
        $data['baojiaCount'] = $this->baojiaCount;
        $data['orders'] = $this->orders;
        $view->with('view',$data);
    }


}