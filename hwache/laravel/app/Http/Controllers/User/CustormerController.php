<?php
/**
 * Created by PhpStorm.
 * Date: 2016/7/19
 * Time: 13:25
 */

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

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
        $Check = HgDailiDealer::where('dl_id', $dl_id)->where('d_id', $dealer_id)->where('dl_status', '<>', 3)->first();
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
        $shenfen_id = $this->request->input('shenfen_id');  //身份
        $dl_id = session('user.member_id');
        $dealer_id = $this->request->input('dealer_id');
        if ($type == 'filelist') {
            $data['file'] = HgAgentFiles::getAgentFileListByShenfen($car_use_type, $shenfen_id, $dealer_id, $dl_id);
            return $data;
            //dd($data);
        }

        if ($type == 'occasions') {     //修改了场合对应的文件选择
            $id = $this->request->input('occasions');
            $data = DB::table('hg_file')->where('cate_id', $id)->get();
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
            $data ['customer_shenfen'] = $shenfen_id;
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
            $checkExist = DB::table('hg_file_agent')
                ->where('cate_id', $data['cate_id'])
                ->where('file_id', $data['file_id'])
                ->where('agent_id', $data['agent_id'])
                ->where('dealer_id', $data['dealer_id'])
                ->where('car_use_type', $data['car_use_type'])
                ->where('customer_shenfen', $data['customer_shenfen'])
                ->where('status', 0)
                ->count();
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
        $Check = HgDailiDealer::where('dl_id', $daili_id)->where('d_id', $dealer_id)->where('dl_status', '<>',
            3)->first();
        if (!$Check) {
            die('经销商不存在');
        }
        $data = HgDelaerWorkday::getWorkTime($daili_id, $dealer_id);
        if (!empty($data)) {
            $view['data'] = $data;
        } else {
            $view['data'] = array();
        }
        return view('dealer.ucenter.dealer_work_time', $view)->with('dealer_id', $dealer_id);
    }

    //添加工作时间或修改工作时间
    public function postWorkTime()
    {
        $data = $this->request->except('_token');
        $data['daili_id'] = session('user.member_id');
        $data['daili_dealer_id'] = HgDailiDealer::getDailiDealerId(session('user.member_id'), $data['dealer_id']);
        $work_id = HgDelaerWorkday::getWorkTime($data['daili_id'], $data['dealer_id']);
        if (!empty($work_id)) {
            $data['day_1'] = $this->request->input('day_1', 0);
            $data['day_2'] = $this->request->input('day_2', 0);
            $data['day_3'] = $this->request->input('day_3', 0);
            $data['day_4'] = $this->request->input('day_4', 0);
            $data['day_5'] = $this->request->input('day_5', 0);
            $data['day_6'] = $this->request->input('day_6', 0);
            $data['day_7'] = $this->request->input('day_7', 0);
            return HgDelaerWorkday::where('dealer_id', $data['dealer_id'])->update($data);
        } else {
            return HgDelaerWorkday::insertGetId($data);
        }
    }


}