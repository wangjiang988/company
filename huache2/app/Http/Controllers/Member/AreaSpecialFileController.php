<?php
/**
 * 上牌特殊文件
 */
namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AreaSpecialFile;

class AreaSpecialFileController extends Controller
{
    protected $user_id;
    protected $upload;
    const IMG_ACTION_TYPE = 'special_file';
    const NAV_STR = 'myDownload';
    const TAB_NAV = 'special';
    const NAV_FILE = 'myFile';
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showList(Request $request)
    {
        $keyword = $request->get('keyword','');
        $search['keyword'] = $keyword;
        $where['user_id'] = $request->user()->id;
        if($keyword != ''){
            $where['file_name'] = ['like','%'.$keyword.'%'];
        }
        $resultObj = AreaSpecialFile::pageList($where);
        $result = $resultObj->toArray();
        if(count($result['data']) > 0){
            $data['list'] = $result['data'];
        }
        $data['pages'] = [
            "total" => $result['total'],
            "per_page" => $result['per_page'],
            "current_page" => $result['current_page'],
            "last_page" => $result['last_page']
        ];
        $data['title'] = "上牌特殊文件-列表";
        $data['nav']   = self::NAV_FILE;
        $data['tab']   = self::TAB_NAV;
        $data['search'] = $search;
        return view('HomeV2.User.SpecialFile.list')->with($data);
    }

    public function showAdd()
    {
        $data['title'] = "上牌特殊文件-新增";
        $data['nav']   = self::NAV_FILE;
        $data['tab']   = self::TAB_NAV;
        return view('HomeV2.User.SpecialFile.add')->with($data);
    }

    public function showView(Request $request,$id)
    {
        $find = AreaSpecialFile::find($id);
        if(is_null($find)){
            exit('<h1>404</h1>');
        }
        switch($find->status){
            case 0:
                $data['title'] = "上牌特殊文件-查看";
                $tpl = 'HomeV2.User.SpecialFile.view';
                break;
            case 1:
                $data['title'] = "上牌特殊文件-审核通过";
                $tpl = 'HomeV2.User.SpecialFile.ok';
                break;
            case 4:
                $data['title'] = "上牌特殊文件-审核驳回";
                $tpl = 'HomeV2.User.SpecialFile.error';
                break;
        }
        $data['find']  = $find;
        $data['nav']   = self::NAV_FILE;
        $data['tab']   = self::TAB_NAV;
        return view($tpl)->with($data);
    }
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showDownloadList()
    {
        $data['title'] = "上牌特殊文件-下载";
        $data['nav']   = self::NAV_STR;
        return view('HomeV2.User.SpecialFile.download')->with($data);
    }

    public function postSave(Request $request)
    {
        if($request->isMethod('post')){
            $data = $request->except('_token','_url','province');
            $data['area_id'] = \App\Models\Area::getNameToId($data['city']);
            $data['area_level'] = 2;
            $data_child = [
                1 => '国内其他非限牌城市户籍居民',
                2 => 'id_gn',
                3 => '中国军人',
                4 => 'id_gj',
                5 => '上牌地本市注册企业（增值税一般纳税人）',
                6 => '上牌地本市注册企业（小规模纳税人）'
            ];
            if($data['licence_user_type'] !=2){
                if(in_array($data['child_type'],[2,4])){
                    $data['licence_other'] = $data[$data_child[$data['child_type']]];
                }else{
                    $data['licence_other'] = $data_child[$data['child_type']];
                }
            }
            $userId = $request->user()->id;
            if($request->hasFile('file_url')){
                $isCart_img = upFile($request->file('file_url'),self::IMG_ACTION_TYPE,$userId);
                if(!$isCart_img){
                    return setJsonMsg(0,'图片上传错误！');
                }
                $data['file_url'] = $isCart_img;
            }else{
                $data['file_url'] = '';
            }
            $data['user_id']    = $userId;
            $res = AreaSpecialFile::saves($data);
            $result = ($res) ? setJsonMsg(1,'文件发布成功！') : setJsonMsg(0,'文件发布失败！') ;
            return response()->json($result);
        }
    }
}
