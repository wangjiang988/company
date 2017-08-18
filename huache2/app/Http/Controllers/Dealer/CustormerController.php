<?php
/**
 * Created by PhpStorm.
 * Date: 2016/7/19
 * Time: 13:25
 */

namespace App\Http\Controllers\Dealer;

use App\Models\HgBaojia;
use App\Models\HgFiles;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\HgAgentFiles;
use App\Models\HgDelaerWorkday;
use App\Models\HgFileCate;
use DB;
use Config;
use App\Models\HgDailiDealer;


class CustormerController extends Controller
{
    private $request;

    public function __construct(Request $request)
    {
        $this->middleware('auth.seller');
        $this->request = $request;
    }

    //读取当前用户的文件信息
    public function getCustfile($id)
    {
        $dl_id = session('user.member_id');
        $view['daili_dealer_id'] = HgDailiDealer::getDailiDealerId($dl_id, $id);
        $view['flag'] = 'custorfile' . $view['daili_dealer_id'];
        $dealer_id = $id;
        //判断经销商存不存在
        $Check = HgDailiDealer::where('dl_id', $dl_id)->where('d_id', $dealer_id)->where('dl_status', 2)->first();
        if (!$Check) {
            die('经销商不存在');
        }
        $view['dealer_id'] = $id;

        return view('dealer.ucenter.dealer_custormer_file', $view);
    }


    //ajax请求
    public function ajaxCustfile($type)
    {
        $car_use_type = $this->request->input('type_id', 0); //0个人,1公司
        if (!$car_use_type) {
            $car_use_type = 0;
        }
        $shenfen_id = $this->request->input('shenfen_id');  //身份
        $dl_id = session('user.member_id');
        $dealer_id = $this->request->input('dealer_id');
        if ($type == 'filelist') {
            if ($car_use_type == 2) {
                $data['file']
                    = HgAgentFiles::getAgentFileListByShenfen($car_use_type,
                    $dealer_id, $dl_id);
            } else {
                $data['file']
                    = HgAgentFiles::getAgentFileListByShenfen($car_use_type,
                    $dealer_id, $dl_id, $shenfen_id);
            }
            return $data;
        }

        if ($type == 'occasions') {     //修改了场合对应的文件选择
            $data = $this->request->except('_token');
            if ($data['car_use_type'] != 2) {
                $data['hc_dealer_identity.identity_id'] = $data['identity_id'];
                unset($data['identity_id']);
            } elseif (isset($data['identity_id'])) {
                unset($data['identity_id']);
            }
            $data = HgFiles::getfileIdentity($data);
            if (empty($data)) {
                $data = array();
            }
            return json_encode($data);
        }
        //新增文件场合
        if ($type == 'add') {
            $cate = $this->request->input('cate');
            $dealer_id = $this->request->input('dealer_id');
            return HgAgentFiles::saveFileCate($cate, $dl_id, $dealer_id);
        }
        if ($type == 'edit') {
            $id = $this->request->input('id');
            $data['num'] = $this->request->input('num');
            $data['dealer_id'] = $dealer_id;
            $data['agent_id'] = $dl_id;
            if ($this->request->hasFile('pic')) {
                $photo = $this->request->file('pic');
                if (!empty($photo) && $photo->isValid()) {
                    $type = $photo->getClientOriginalExtension();
                    if (!allowext($type)) {
                        return [
                            'msg' => '文件类型不允许'
                        ];
                    }
                    $fileName = session('user.member_id') . '_' . date('YmdHms') . mt_rand(1000, 9999) . '.' . $type;
                    $filePath = 'file/' . date("Y") . '/' . date("m") . '/';
                    $photo->move(config('app.uploaddir') . $filePath, $fileName);
                    $data['file_url'] = $filePath . $fileName;
                }
            }
            return HgAgentFiles::saveAgentFile($id, $data);

        }
        if ($type == 'add_comment') {
            $data['isself'] = $this->request->input('use-occasions-file-radio');
            $data['num'] = $this->request->input('num', 1);
            $data['cate_id'] = $this->request->input('type');
            $data['file_id'] = $this->request->input('file_id');
            $data['daili_dealer_id'] = HgDailiDealer::getDailiDealerId(session('user.member_id'), $dealer_id);
            $data['car_use_type'] = $car_use_type;
            $data ['customer_shenfen'] = $shenfen_id ? $shenfen_id : 0;
            $data['agent_id'] = $dl_id;
            $data['dealer_id'] = $dealer_id;
            $photo = $this->request->file('pic');
            if (!empty($photo) && $photo->isValid()) {
                $type = $photo->getClientOriginalExtension();
                if (!allowext($type)) {
                    return [
                        'msg' => '文件类型不允许'
                    ];
                }
                $fileName = session('user.member_id') . '_' . date('YmdHms') . mt_rand(1000, 9999) . '.' . $type;
                $filePath = 'file/' . date("Y") . '/' . date("m") . '/';
                $photo->move(config('app.uploaddir') . $filePath, $fileName);
                $data['file_url'] = $filePath . $fileName;
            } else {
                $data['file_url'] = '';
            }
            return HgAgentFiles::saveAgentFile(0, $data);
        }
        if ($type == 'del') {
            $id = $this->request->input('id');
            return HgAgentFiles::delAgentFile($id, $dealer_id, $dl_id);
        }

    }


    //工作时段
    public function getWorkTime($id)
    {
        $dealer_id = $id;
        $daili_id = session('user.member_id');
        $view['daili_dealer_id'] = HgDailiDealer::getDailiDealerId($daili_id, $id);
        $view['flag'] = 'worktime' . $view['daili_dealer_id'];
        //判断经销商存不存在
        $Check = HgDailiDealer::where('dl_id', $daili_id)->where('d_id', $dealer_id)->where('dl_status',
            2)->first();
        if (!$Check) {
            die('经销商不存在');
        }
        $data = HgDelaerWorkday::getWorkTime($daili_id, $dealer_id);
        if (!empty($data)) {
            $view['data'] = $data;
        } else {
            $view['data'] = array();
        }
        $today   =  Carbon::today()->toDateString();
        $dayofweek =  get_day_of_week(Carbon::today());

        $view['today']  =  $today;
        $view['day_of_week']  =  $dayofweek;

        if(!empty($view['data'])){
            $result = $this->_get_rest_days($view['data']->toArray());
            //判断今天是否是工作日
            $rest_days = $result['no_work_day'];
            $view['rest_days']    =  $rest_days;
            $view['is_rest_day']  =  $result['is_rest_day'];
        }

        return view('dealer.ucenter.dealer_work_time', $view)->with('dealer_id', $dealer_id);
    }


    //获取休息日,并返回今天是否是休息日
    //work_time toArray
    private function _get_rest_days(array $work_time)
    {
        $no_work_days = [];
        $d_today = Carbon::today();
        $today   =  $d_today->toDateString();
        if(strcmp($today,$work_time['rest_1_end'])<=0){
            $no_work_days[] = [
                'index' =>1,
                'start'=>$work_time['rest_1_start'],
                'end'=>$work_time['rest_1_end'],
            ];
        }
        if(strcmp($today,$work_time['rest_2_end'])<=0){
            $no_work_days[] = [
                'index' =>2,
                'start'=>$work_time['rest_2_start'],
                'end'=>$work_time['rest_2_end'],
            ];
        }

        $is_rest_day1 = $d_today->between(Carbon::parse($work_time['rest_1_start']),Carbon::parse($work_time['rest_1_end']));
        $is_rest_day2 = $d_today->between(Carbon::parse($work_time['rest_2_start']),Carbon::parse($work_time['rest_2_end']));
        $is_rest_day  = false;
        if($is_rest_day1 || $is_rest_day2)
            $is_rest_day = true;
        $day_of_week        =  $d_today->dayOfWeek;
        if(!$work_time['day_'.$day_of_week]) $is_rest_day = true;  //非工作日

        return ['no_work_day'=>$no_work_days, 'is_rest_day'=>$is_rest_day];
    }

    //添加工作时间或修改工作时间
    public function postWorkTime()
    {
        $data = $this->request->except('_token');
        $data['daili_id'] = session('user.member_id');
        $data['daili_dealer_id'] = HgDailiDealer::getDailiDealerId(session('user.member_id'), $data['dealer_id']);
        $work_time = HgDelaerWorkday::getWorkTime($data['daili_id'], $data['dealer_id']);

        $ret     = false;
        if (!empty($work_time)) {
            $data['day_1'] = $this->request->input('day_1', 0);
            $data['day_2'] = $this->request->input('day_2', 0);
            $data['day_3'] = $this->request->input('day_3', 0);
            $data['day_4'] = $this->request->input('day_4', 0);
            $data['day_5'] = $this->request->input('day_5', 0);
            $data['day_6'] = $this->request->input('day_6', 0);
            $data['day_0'] = $this->request->input('day_0', 0);
            HgDelaerWorkday::where('dealer_id', $data['dealer_id'])->update($data);
            $work_id   = $work_time->work_time_id;

            $ret = true;
        } else {
            $work_id =  HgDelaerWorkday::insertGetId($data);
            if($work_id)  $ret = true;
        }
        if($ret) {
            $work_time =  HgDelaerWorkday::where('id',$work_id)->firstOrFail();
            $member_id =  session('user.member_id');
            //取消成功， 判断今天是否是非工作日或工作日且非工作时间 改为华车下架数据。
            //如果是工作时间，立即上架。
            if($this->_check_rest_time($work_time))
            {
                HgBaojia::set_hwache_baojia_end($member_id, $work_time->dealer_id,$work_time->daili_dealer_id);
            }else{
                HgBaojia::set_hwache_baojia_start($member_id, $work_time->dealer_id,$work_time->daili_dealer_id);
            }
            return response()->json([
                'code'  => '200',
                'msg'   =>  '修改成功'
            ]);

        }else{
            return response()->json([
                'code' => '500',
                'msg'   =>  '修改失败'
            ]);
        }

    }

    //取消非工作的休息日程
    public function cancel_rest_day(Request $request)
    {
        $data = $request->except('_token');
        if(!$data['id'] || !$data['daili_dealer_id'])
        {
            return response()->json([
                'code' => '1000',
                'msg' =>  "参数错误"
            ]);
        }
        $member_id = session('user.member_id');
        $work_time =  HgDelaerWorkday::where('id', $data['id'])
                                    ->where('daili_dealer_id', $data['daili_dealer_id'])
                                    ->firstOrFail();
        if($work_time->daili_id != $member_id){
            return response()->json([
                'code' => '401',
                'msg' =>  '您没有权限修改'
            ]);
        }



        if($data['index'] == 1)
        {
            $work_time->rest_1_start = '0000-00-00';
            $work_time->rest_1_end   = '0000-00-00';
        }else{
            $work_time->rest_2_start = '0000-00-00';
            $work_time->rest_2_end   = '0000-00-00';
        }
        $ret  = $work_time->save();
        if($ret) {
            //取消成功， 判断今天是否是非工作日或工作日且非工作时间 改为华车下架数据。
            //如果是工作时间，立即上架。
            if($this->_check_rest_time($work_time))
            {
                HgBaojia::set_hwache_baojia_end($member_id, $work_time->dealer_id,$work_time->daili_dealer_id);
            }else{
                HgBaojia::set_hwache_baojia_start($member_id, $work_time->dealer_id,$work_time->daili_dealer_id);
            }
            return response()->json([
                'code' => '200',
                'msg'   =>  '取消成功'
            ]);

        }
        else{
            return response()->json([
                'code' => '500',
                'msg'   =>  '取消失败'
            ]);
        }
    }


    /**
     * 检查报价对象是否在工作时间内
     * @param $id
     */
    private function _check_rest_time(HgDelaerWorkday $work_time){
        $now        =   Carbon::now();
        $today_string= date("Y-m-d");
        $today      =   Carbon::parse($today_string);


        $hwache_am_start = Carbon::parse($today_string)->addHour(9);
        $hwache_am_end   = Carbon::parse($today_string)->addHour(12);
        $hwache_pm_start = Carbon::parse($today_string)->addHour(13);
        $hwache_pm_end   = Carbon::parse($today_string)->addHour(17);


        $is_rest_time  = true;

        if(!$work_time){  //没有工作时间记录  使用华车时间
            if($now->between($hwache_am_start,$hwache_am_end)|| $now->between($hwache_pm_start, $hwache_pm_end))
            {
                $is_rest_time  = false;
            }else{
                $is_rest_time  = true;
            }
        }else{
            //查看是否工作日
            $is_rest_day1 = $today->between(Carbon::parse($work_time['rest_1_start']),Carbon::parse($work_time['rest_1_end']));
            $is_rest_day2 = $today->between(Carbon::parse($work_time['rest_2_start']),Carbon::parse($work_time['rest_2_end']));

            if($is_rest_day1 || $is_rest_day2)  return true;

            $day_of_week        =  $today->dayOfWeek;
            $work_time_array  = $work_time->toArray();
            if(!$work_time_array['day_'.$day_of_week])  return true;  //非工作日

            //查看是否工作时段
//            if(($hour>=$work_time->am_start && $hour<=$work_time->am_end) || ($hour>=$work_time->pm_start && $hour<=$work_time->pm_end))
            if($now->between(Carbon::parse($today_string.' '.$work_time->am_start), Carbon::parse($today_string.' '.$work_time->am_end))
                || $now->between(Carbon::parse($today_string.' '.$work_time->pm_start), Carbon::parse($today_string.' '.$work_time->pm_end)) )
            {
                $is_rest_time  = false;
            }else{
                $is_rest_time  = true;
            }

        }
        return $is_rest_time;
    }


}