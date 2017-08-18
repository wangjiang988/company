<?php namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HgGoodsClass;
use DB;
use App\Models\Area;
use Session;
use App\Services\Seccode;

class IndexController extends Controller {

    /**
     * 首页控制器
     */
    public function index(Request $request)
    {
        $date = date("Y,m,d,H,i,s", time());
        return view('index.index')->with('date', $date);
    }

    // 首页车型联动
    public function getbrand($id){
        $allCar = config('car');
        $tmp = $allCar[$id];
        $goods_class = array();
        foreach($tmp as $k =>$v){
            $goods_class[] = $v;
        }

        //$goods_class = DB::table('goods_class')->select('gc_id', 'gc_name')->where('gc_parent_id', '=', $id)->get();
        return json_encode($goods_class);
    }

    /**
     * 缓存城市 、 车型等等
     *
     */
    public function excuteCache($type)
    {
        if (isset($_REQUEST['admin']) && $_REQUEST['admin'] == 'yeath') {
            if ($type == 'area') {
                $areaArray = Area::where('not_mainland','0')->get()->toArray();
                $area = array();
                foreach ($areaArray as $K => $v) {
                    $area[$v['area_parent_id']][$v['area_id']]['name'] = $v['area_name'];
                    $area[$v['area_parent_id']][$v['area_id']]['area_id'] = $v['area_id'];
                    $area[$v['area_parent_id']][$v['area_id']]['first_letter'] = $v['first_letter'];
                    $area[$v['area_parent_id']][$v['area_id']]['area_chepai'] = $v['area_chepai'];
                    $area[$v['area_parent_id']][$v['area_id']]['area_xianpai'] = $v['area_xianpai'];
                }
                $file = config_path('area.php');
                file_put_contents($file, "<?php \r\n return " . var_export($area, true) . "; \r\n?>");
                file_put_contents(public_path('js/area.js'), "var area=" . json_encode($area));
                echo '更新地区缓存成功';
            } elseif ($type == 'car') {
                $carArray = HgGoodsClass::get()->toArray();
                $car = array();
                foreach ($carArray as $K => $v) {
                    $car[$v['gc_parent_id']][$v['gc_id']] = array('gc_id' => $v['gc_id'], 'gc_name' => $v['gc_name']);
                }
                $file = config_path('car.php');
                file_put_contents($file, "<?php \r\n return " . var_export($car, true) . "; \r\n?>");
                file_put_contents(public_path('js/car.js'), "var car=" . json_encode($car));
                echo '更新地区缓存成功';
            } elseif ($type == 'front-area') {
                $result = area::getSecArea();
                foreach ($result as $key => $value) {
                    if ($value['first_letter'] == $result[$key]['first_letter']) {
                        $arr[$value['first_letter']][] = $value;
                    }

                }

                $result_all = area::getSecAreaAll();
                foreach ($result_all as $key2 => $value2) {
                    if ($value2['first_letter'] == $result_all[$key2]['first_letter']) {
                        $arr_all[$value2['first_letter']][] = $value2;
                    }
                }

                ksort($arr);
                ksort($arr_all);
                file_put_contents(public_path('js/vendor/front-area.js'), json_encode($arr));
                file_put_contents(public_path('js/vendor/front-area_all.js'), json_encode($arr_all));
                echo '更新地区缓存成功';
            } elseif ($type == 'car-all') {
                $brand = HgGoodsClass::where('gc_parent_id', 0)
                    ->select('gc_id', 'gc_name', 'detail_img')
                    ->get()
                    ->toArray();
                file_put_contents(public_path('js/vendor/brand.js'),json_encode($brand));
                echo '更新品牌缓存成功';
            } elseif ($type == 'car-info') {
                $result = HgGoodsClass::where('gc_parent_id', '<>', 0)
                    ->orderBy('gc_parent_id')
                    ->select('gc_id', 'gc_name', 'gc_parent_id')
                    ->get()
                    ->toArray();
                foreach ($result as $key => $value) {
                    $rest[$value['gc_parent_id']][] = $value;
                }
                file_put_contents(public_path('js/vendor/car-info.js'), json_encode($rest));
                echo '更新车型缓存成功';
            }
        } else {
            echo "非法操作";
        }
        echo "<script>setTimeout('history.back(-1)',6000)</script>";
    }



    /**
     * 产生验证码
     *
     */
    public function makeCode(Request $request){
    	$seccode = makeSeccode();
    	$request->session()->put('user_code',$seccode);
    	$request->session()->save();

    	@header("Expires: -1");
    	@header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
    	@header("Pragma: no-cache");

    	$code = new Seccode();
    	$code->code = $request->session()->get('user_code');
    	$code->width = 90;
    	$code->height = 26;
    	$code->background = 1;
    	$code->adulterate = 1;
    	$code->scatter = '';
    	$code->color = 1;
    	$code->size = 1;
    	$code->shadow = 1;
    	$code->animator = 0;
    	$code->datapath =  base_path('storage/seccode/');
    	$code->display();
    }

    /**
     * AJAX验证
     *
     */
    public function checkCode(){
    	if (checkSeccode($_GET['captcha'])){
    		$data['error'] = '0';
    	}else{
    		$data['error'] = '1';
    	}
    	echo json_encode($data);
    }


}
