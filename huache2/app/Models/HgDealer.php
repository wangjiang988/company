<?php namespace App\Models;

/**
 * 经销商模型
 */
use Illuminate\Database\Eloquent\Model;
use Cache;
class HgDealer extends Model {

    protected $table = 'hg_dealer';

    public $timestamps = false;
    public static function getDealerInfo($id) {
    	$cacheName = 'Dealer' . $id;
    	if (!Cache::has($cacheName)) {
    		$cacheData = [];
    		$d = self::where('d_id', $id)->get();
    		if (!empty($d)) {
    			foreach ($d as $key => $value) {
    				$cacheData=$value;
    			}
    			
    		}
    	}else {
            $cacheData = Cache::get($cacheName);
        }
        return $cacheData;

    }

    public function orders()
    {
        return $this->hasMany(Hgorder::class, 'dealer_id', 'd_id');
    }

    //查出所有的订单所属经销商
    public function getDealer($type, $seller, $field)
    {
        return self::whereHas('orders', function ($query) use ($type, $seller, $field) {
            $query->where('seller_id',$seller);
            if ($type == 'actives') {
                $query->whereNotIn('order_state', $field)
                    ->where('order_status','<>',1)
                    ->where('order_state','<>',200);
            } else {
                $query->whereIn('order_state', $field);
            }
        })->groupBy('d_name')->get(['d_name']);
    }

}
