<?php

namespace App\Http\Controllers\Front;

use App\Models\HgBaojia;
use App\Models\HgBaojiaXzj;
use App\Models\HgCartLog;
use App\Models\HgEditInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BaseParticularController extends Controller
{
    protected $baojia;

    /**
     * BaseParticularController constructor.
     *
     * @param HgBaojia  $baojia
     * @param HgCartLog $cartLog
     */
    public function __construct(HgBaojia $baojia)
    {
        $this->baojia = $baojia;
    }

    public function getEditInfo($order)
    {
        $arr_info = HgEditInfo::getEditfile($order->order_sn);
        $arr_xzj = HgBaojiaXzj::getXzjType($order->bj_id);
        $arr_zp = $order->orderServer;
        if (!empty($arr_info['xzj'])) {
            $result['rpos'] = $arr_info['xzj'];
        } else {
            $result['rpos'] = isset($arr_xzj['rpo']) ? $arr_xzj['rpo'] : [];
        }
        if ( !empty($arr_info['zengpin'])) {
            $result['zp'] = $arr_info['zengpin'];
        } else {
            $result['zp'] = ($arr_zp->count()) ? $arr_zp->toArray() : [];
        }
        return $result;
    }

    public function getAllEditInfo($order_sn)
    {
        $datas = HgEditInfo::getComment($order_sn);
        $result = [];
        if ( ! empty($datas['xzj'])) {
            foreach ($datas['xzj'] as $key=>$xzj) {
                if ($xzj['num'] != $xzj['old_num']) {
                    $result[$key]['updated_at'] = $datas['updated_at'];
                    $result[$key]['title'] = $xzj['xzj_title'];
                    $result[$key]['num'] = $xzj['num'];
                    $result[$key]['old_num'] = $xzj['old_num'];
                    $result[$key]['status'] = $datas['status'];
                    $result[$key]['created_at'] = $datas['createat'];
                }
            }
            $num = count($datas['xzj']);
        }
        if ( ! empty($datas['zengpin'])) {
            $num = isset($num) ? $num : 0;
            foreach ($datas['zengpin'] as $key => $zp) {
                if ($zp['num'] != $zp['old_num']) {
                    $result[$num + $key]['updated_at'] = $datas['updated_at'];
                    $result[$num + $key]['title'] = $zp['zp_title'];
                    $result[$num + $key]['num'] = $zp['num'];
                    $result[$num + $key]['old_num'] = $zp['old_num'];
                    $result[$num + $key]['status'] = $datas['status'];
                    $result[$num + $key]['created_at'] = $datas['createat'];
                }
            }
        }
        return $result;
    }

    public function getSpecialFiles($order)
    {
        $files = HgEditInfo::getFeedfile($order->order_sn,201);
        if ($order->orderAttr->new_file_comment) {
            $file_comment = explode('、', $order->orderAttr->new_file_comment);
            foreach ($files as $key => $file) {
                if ( ! in_array($file['title'], $file_comment) && $file['ok'] != 'N') {
                    unset($files[$key]);
                }
            }
        } else {
            foreach ($files as $key => $file) {
                if ($file['ok'] != 'N') {
                    unset($files[$key]);
                }
            }
        }
        return $files;
    }
}
