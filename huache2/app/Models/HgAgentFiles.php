<?php namespace App\Models;

/**
 * 代理添加的文件资料模型
 *$agent_id  代理id
 *$cate_id   文件分类id  1投保 2上牌 3 临牌 4 领取国家节能补贴 5 其他
 */
use Illuminate\Database\Eloquent\Model;
use Cache;
use DB;

class HgAgentFiles extends Model
{

    protected $table = 'hg_file_agent';

    public $timestamps = false;
    // 取得代理添加的某个分类的文件

    use Common;

    public static function getFiles($agent_id, $cate_id)
    {
        $cacheName = 'myfiles' . $agent_id . $cate_id;
        // Cache::forget($cacheName);
        if (!Cache::has($cacheName)) {
            $cacheData = self::leftJoin(
                'hg_file',
                'hg_file_agent.file_id',
                '=',
                'hg_file.file_id')->leftJoin('hg_file_cate', 'hg_file.cate_id', '=',
                'hg_file_cate.cate_id')->where('hg_file_agent.agent_id', '=', $agent_id)->where('hg_file.cate_id', '=',
                $cate_id)->get();
            if (!config('app.debug')) {
                Cache::put($cacheName, $cacheData, config('app.cache_time'));
            }
        } else {

            $cacheData = Cache::get($cacheName);
        }
        return $cacheData;
    }

    public static function getFilesbyName($agent_id, $catename)
    {
        $cacheName = 'myfiles' . $agent_id . Pinyin($catename, 1);

        // Cache::forget($cacheName);
        if (!Cache::has($cacheName)) {
            $cacheData = self::leftJoin(
                'hg_file',
                'hg_file_agent.file_id',
                '=',
                'hg_file.file_id')->leftJoin('hg_file_cate', 'hg_file.cate_id', '=',
                'hg_file_cate.cate_id')->where('hg_file_agent.agent_id', '=', $agent_id)->where('hg_file_cate.cate',
                '=', $catename)->get();

            if (!config('app.debug')) {
                Cache::put($cacheName, $cacheData, config('app.cache_time'));
            }
        } else {

            $cacheData = Cache::get($cacheName);
        }

        return $cacheData;

    }

    /**
     * 新增文件场合 add by jerry
     * @param  $cate
     * @param  $dl_id 如果系统添加此为0
     * @param  $dealer_id 如果系统添加此为0
     * @param number $regular
     * @return multitype:number string
     */

    public static function saveFileCate($cate, $dl_id, $dealer_id, $regular = 1)
    {
        $checkExist = DB::table('hg_file_cate')
            ->where('cate', $cate)
            ->count();
        if ($checkExist >= 1) {
            return array('error_code' => 1, 'msg' => '此场合已经存在');
        }
        $data = array('cate' => $cate, 'dl_id' => $dl_id, 'dealer_id' => $dealer_id, 'regular' => 1);
        $e = DB::table('hg_file_cate')->insertGetId($data);

        if (!$e) {
            return array('error_code' => 1, 'msg' => '新的场合保存失败');
        } else {
            return array('error_code' => 0, 'msg' => '新的场合保存成功');
        }

    }

    /**
     * 保存文件 add by jerry
     * @param unknown $id
     * @param unknown $data [file_id,
     *                        agent_id,
     *                        dealer_id,
     *                        car_use_type,
     *                        customer_shenfen,
     *                        num,
     *                        file_url]
     * @return multitype:number string
     */
    public static function saveAgentFile($id, $data)
    {
        if ($id == 0) {//新增
            $checkExist = DB::table('hg_file_agent')
                ->where('cate_id', $data['cate_id'])
                ->where('file_id', $data['file_id'])
                ->where('agent_id', $data['agent_id'])
                ->where('daili_dealer_id', $data['daili_dealer_id'])
                ->where('car_use_type', $data['car_use_type'])
                ->where('customer_shenfen', $data['customer_shenfen'])
                ->where('isself', $data['isself'])
                ->where('status', 0)
                ->count();
            if ($checkExist >= 1) {//检查重复
                return array('error_code' => 1, 'msg' => '同一场合同一文件只能添加一次');
            }
            $e = self::insertGetId($data);
        } else {//编辑更新
            $checkExist = DB::table('hg_file_agent')
                ->where('agent_id', $data['agent_id'])
                ->where('dealer_id', $data['dealer_id'])
                ->where('id', $id)
                ->count();
            if ($checkExist > 1) {//检查重复
                return array('error_code' => 1, 'msg' => '同一场合同一文件只能添加一次');
            }
            $e = self::where('id', $id)->update($data);
            $tmp = self::where('id', $id)->first();
            $data['file_url'] = $tmp['file_url'];

        }
        if ($e === false) {
            return array('error_code' => 1, 'msg' => '文件保存失败');
        } else {
            return array('error_code' => 0, 'msg' => '文件保存成功', 'pic_path' => $data['file_url'], 'id' => $e);
        }
    }

    /**
     * 删除文件 add by jerry
     * @param unknown $id
     * @return multitype:number string
     */
    public static function delAgentFile($id, $dealer_id, $dl_id)
    {
        $e = self::where('id', $id)
            ->where('dealer_id', $dealer_id)
            ->where('agent_id', $dl_id)
            ->update(['status' => 2]);
        if ($e) {
            return [
                'error_code' => 1
            ];
        } else {
            return [
                'error_msg' => '删除失败'
            ];
        }

    }

    /**
     * 根据车辆用途和身份 获取文件列表 add by jerry
     * @param  $car_type
     * @param  $shenfen
     * @param  $dealer_id
     * @param  $dl_id
     * @return array:
     */
    public static function getAgentFileListByShenfen($car_type, $dealer_id, $dl_id,$shenfen=null)
    {
        $list = DB::table('hg_file_agent')
            ->join('hg_file', 'hg_file_agent.file_id', '=', 'hg_file.file_id')
            ->join('hg_daili_dealer', 'hg_file_agent.daili_dealer_id', '=', 'hg_daili_dealer.id')
            ->where('hg_daili_dealer.dl_status', '<>', 3)
            ->where('hg_file_agent.agent_id', $dl_id)
            ->where('hg_file_agent.status', 0)
            ->where('hg_file_agent.dealer_id', $dealer_id)
            ->where('hg_file_agent.car_use_type', $car_type)
            ->where(function ($query) use ($shenfen){
                if ( ! is_null($shenfen)) {
                    $query->where('hg_file_agent.customer_shenfen', $shenfen);
                }
            })->select('hg_file_agent.*', 'hg_file.title')
            ->get();
        $tmp = array();
        if (count($list) > 0) {
            foreach ($list as $k => $v) {
                $tmp[$v->cate_id][] = get_object_vars($v);
            }
        }
        return $tmp;
    }

    public static function getAgentFileList($car_type, $daili_dealer_id,$shenfen)
    {
        return DB::table('hg_file_agent')
            ->join('hg_file', 'hg_file_agent.file_id', '=', 'hg_file.file_id')
            ->join('hg_daili_dealer', 'hg_file_agent.daili_dealer_id', '=', 'hg_daili_dealer.id')
            ->where('hg_daili_dealer.dl_status', '<>', 3)
            ->where('hg_file_agent.status', 0)
            ->where('hg_file_agent.daili_dealer_id', $daili_dealer_id)
            ->where('hg_file_agent.car_use_type', $car_type)
            ->where('hg_file_agent.customer_shenfen', $shenfen)
            ->select('hg_file_agent.*', 'hg_file.title')
            ->get();

    }

    /**
     * @param array $data
     * @return mixed collect
     */
    public static function getFileLists(array $data)
    {
        return DB::table('hg_file_agent')
            ->join('hg_file', 'hg_file_agent.file_id', '=', 'hg_file.file_id')
            ->whereIn('hg_file_agent.id', $data)
            ->select('hg_file_agent.*', 'hg_file.title')
            ->get();
    }
}
