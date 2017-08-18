<?php
/**
 * Created by PhpStorm.
 * User: Qinlin
 * Date: 2016/11/8
 * Time: 13:05
 */
function sellerStatus($stauts){
    switch($stauts){
        case 0:
            return '未审核';
            break;
        case 1:
            return '审核通过';
            break;
        case 2:
            return '冻结';
            break;
    }
}

/**
 * 模板 url设置函数
 * @param $action 路径
 * @param string $param 参数
 * @return string
 */
function setUrl($action,$param=''){
    $pathArr = explode('/',$action);
    $url     = 'act='.$pathArr[0].'&op='.$pathArr[1];
    $_param  = empty($param) ? '' : '&'.$param;
    return "index.php?".$url.$_param;
}

/**
 * 判断语句
 * @param $judge      判断条件数组 示例['12' , '>' ,'10']
 * @param null $type  判断的类型
 * @return mixed|string
 */
function isSelected($judge,$type=null){
    $res = eval("return ($judge[0] $judge[1] $judge[2]);");
    switch($type){
        case 'select':
            return ($res)?'selected="selected"':'';
            break;
        case 'checkbox':
        case 'radio':
            return ($res)?'checked="checked"':'';
            break;
        default:
            return $res;
    }
}

/**
 * 获取地区名称或其他字段
 * @param $region_id
 * @param string $field
 * @param bool $one
 * @return mixed
 */
function getRegion($region_id,$field='area_name',$one=true){
    $region   = Model('common','area');
    $option['table'] = 'area';
    $option['where'] = ['area_id'=>$region_id];
    $option['field'] = $field;
    $result = $region->getFind($option);
    return ($one)?$result[$field]:$result;
}

/**
 * 生成新的唯一随机号
 * @return string
 */
function newName(){
    return floor(microtime(true)).rand(100,999);
}

/**
 * 异步请求判断
 * @return bool
 */
function isAjax(){
    return ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || !empty($_POST[C('VAR_AJAX_SUBMIT')]) || !empty($_GET[C('VAR_AJAX_SUBMIT')])) ? true : false;
}

/**
 * 给字符串打马赛克
 * @param $str          待处理字符串
 * @param int $trimNum  首尾预留字符位数
 * @return string
 */
function setMSKstring($str='',$trimNum=3){
    if($str =='')
        return $str;

    $lenght = strlen($str);
    if($lenght <= $trimNum*2){
        return setReplace($str);
    }else{
        $explode[0] = substr($str,0,$trimNum);
        $explode[1] = setReplace(substr($str,$trimNum,$lenght-$trimNum*2));
        $explode[2] = substr($str,$lenght-$trimNum,$trimNum);
        return implode('',$explode);
    }
}

/**
 * 字符串替换
 * @param $str
 * @param string $fu
 * @return string
 */
function setReplace($str,$fu='*'){
    $lenght = strlen($str);
    $newStr = '';
    for($i=0;$i<$lenght;$i++){
        $newStr .= $fu;
    }
    return $newStr;
}

function chanageStr($string,$start=0,$lenght=1,$replace='******'){
    return substr_replace($string,$replace,$start,$lenght);
}

function getArrIndex($data='',$index=null,$fun='explode',$que=','){
    if(!$data || is_null($data)) return '';

    switch($fun){
        case 'unserialize':
            $arr = unserialize($data);
            break;
        case 'json_decode':
            $arr = json_decode($data,true);
            break;
        case 'explode':
            $arr = explode($que,$data);
            break;
    }
    if(is_null($index)) return count($arr);
    return isset($arr[$index]) ? $arr[$index] : '';
}

if( ! function_exists('paifang')){
    function paifang($paifang,$china=false){
        $biaozhunArr = ['1'=>'国四','0'=>'国五',2=>'国六',10=>'不限'];
        if($china){
            return $biaozhunArr[$paifang];
        }
        $arr = explode(',',$paifang);
        $newArr = array();
        foreach($arr as $vls){
            $newArr[] = $biaozhunArr[$vls];
        }
        return implode(' / ',$newArr);
    }
}

if( ! function_exists('setHtml') ){
    /**
     * 构建下拉框、单选框、下拉框
     * @param $data               数据源（二维数组）
     * @param $name               元件名称
     * @param string $type        元件类型
     * @param string $value       选中值（一般取数据库保存的值）
     * @param string $_key        数据源表示数据下标对应的名称
     * @param string $_val        数据源表示数据值对应的名称
     * @param string $function    元件对应的触发函数
     */
    function setHtml($data,$name,$type='select',$value='',$_key='id',$_val='name',$function=''){
        switch($type){
            case 'select':
                $chanage = empty($function) ? '' : sprintf('onchange(%s(this.value))',$function);
                $select = sprintf('<select name="%s" %s><option value="">--请选择--</option>',$name,$chanage);

                foreach($data as $item){
                    $selected = ($item[$_key] == $value) ? 'selected="selected"' : '' ;
                    $select .= sprintf('<option value="%s" %s>%s</option>',$item[$_key],$selected,$item[$_val]);
                }
                $select .= '</select>';
                return $select;
                break;
            case 'checkbox':
            case 'radio':
                $checkRadio = '';
                $valueArr = explode(',',$value);
                foreach($data as $item){
                    $selected = (in_array($item[$_key],$valueArr)) ? 'checked="checked"' : '' ;
                    $checkRadio .= sprintf('<input type="%s" value="%s" name="%s" %s />%s',$type,$item[$_key],$name,$selected,$item[$_val]);
                }
                return $checkRadio;
                break;
        }
    }
}

if(!function_exists('getImgidToImgurl')){
    /**
     * 通过图片id查找图片地址
     * @param type $imgId
     */
    function getImgidToImgurl($imgId,$whith=200,$hight=200){
        $qiNiu   = Model('user');
        return $qiNiu->getImgidToImg($imgId)."?imageView2/1/w/{$whith}/h/{$hight}";
    }
}

if( ! function_exists('is_email')){
    /**
     * 检测邮箱格式
     * Auth：qinlin
     * @param $email
     * @return int
     */
    function is_email($email){
        $ereg = '/^[0-9a-zA-Z]+@(([0-9a-zA-Z]+)[.])+[a-z]{2,4}$/i';
        return preg_match($ereg,$email);
    }
}

if( ! function_exists('is_phone')){
    /**
     * 检测手机格式
     * Auth：qinliln
     * @param $phone
     * @return int
     */
    function is_phone($phone){
        $ereg = "/^1[3|4|5|7|8]\d{9}$/";
        return preg_match($ereg,$phone);
    }
}

if( ! function_exists('setJsonMsg') ){
    function setJsonMsg($bool,$msg = '发生错误！',$push=null,$error_code = null){
        $code = is_null($error_code) ? intval($bool==false) : $error_code;
        $datas = ['Success' => $bool , 'Msg' => $msg ,'Error_code' => $code];
        $newData = !is_null($push) ? array_merge($datas,$push) : $datas;
        return json_encode($newData);
    }
}

if( ! function_exists('getUserStatus')){
    //获取用户的状态
    function getUserStatus($id){
        //登录冻结、手机免扰、邮箱免扰
        $model = Model('user');
        //登录状态
        $find = $model->getUserInfo($id);
        echo empty($find['status']) ? '无效' : '有效';
        //免扰状态
    }
}

if( ! function_exists('getFrStatus')){
    //获取用户的状态
    function getFrStatus($id){
        $model = Model('user');
        $find = $model->getUserInfo($id);
        if($find['status'] ==1) {
            $email = $model->getFreeze($id, $find['email'], '`status`', 'pwd_dj');
            echo empty($email['status']) ? '' : '<font class="red">(邮箱找密冻结）</font><br />';
            $mobile = $model->getFreeze($id, $find['phone'], '`status`', 'pwd_dj');
            echo empty($mobile['status']) ? '' : '<font class="red">(手机找密冻结）</font><br />';
            //手机登录冻结
            $where = empty($find['email']) ? $find['phone'] : [$find['phone'], $find['email']];
            $djMobile = $model->getFreeze($id, $where, '`status`', 'dj');
            echo empty($djMobile['status']) ? '' : '<font class="red">(登录冻结）</font>';
        }
    }
}

/**
 * 计算透支时间
 * @param $credit_line
 * @param $created_at
 * @return bool
 */
function getSellerOverdraftTime($credit_line,$created_at){
    if(is_null($created_at)){
        return ($credit_line >=0);
    }
    if ($credit_line >=0) {
         $endDate = strtotime('+20 day',strtotime($created_at));
         return $endDate <= time();
    }else {
        return false;
    }
}

function upOneFile($fileName='_pic',$default_dir='uploads'){
    if (chksubmit()){
        //上传图片
        $upload = new UploadFile();
        $upload->set('thumb_width',	500);
        $upload->set('thumb_height',499);
        $upload->set('thumb_ext','');
        $upload->set('max_size',C('image_max_filesize')?C('image_max_filesize'):1024);
        $upload->set('ifremove',false);
        $upload->set('default_dir',$default_dir);

        if (!empty($_FILES[$fileName]['tmp_name'])){
            $result = $upload->upfile($fileName);
            if ($result){
                return UPLOAD_SITE_URL.'/'.$default_dir.'/'.$upload->thumb_image;
            }else {
                return false;
            }
        }
    }
}

function getDateHover($dat=null,$date){
    if($date == $dat){
        return 'class="button button-primary button-action button-small"';
    }
}

function explodeImg($imgPath=null){
    if(!is_null($imgPath) && $imgPath !=''){
        $imgArr = explode(',',$imgPath);
        $imgHtml = '';
        foreach($imgArr as $img){
            $imgHtml .= '<a href="'.UPLOAD_SITE_URL.$img.'">'.$img.'</a><br/>';
        }
        return $imgHtml;exit;
    }
    return '';exit;
}

if (!function_exists('getActivatedCode')) {
    /**
     * 生成激活码
     *
     * @param int $type 生成随机数池的种类
     * @param int $num  随机数数量
     * @return string
     */
    function getActivatedCode($type = 1, $num = 4)
    {
        // 随机数种子
        $seed = [
            '1234567890',
            'abcdefghijklmnopqrstuvwxyz',
            'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
        ];
        // 根据不同的类别生成随机数池
        switch ($type) {
            case 2: // 小写字母
                $rand = SetRandSting($seed[1],$num);
                break;
            case 3: // 大写字母
                $rand = SetRandSting($seed[2],$num);
                break;
            case 4: // 前两位大写字母+数字
                $rand = SetRandSting($seed[2],2).SetRandSting($seed[0],$num-2);
                break;
            case 5://前两位大写字母+数字+末尾两位大写字母
                $rand = SetRandSting($seed[2],2).SetRandSting($seed[0],$num-4).SetRandSting($seed[2],2);
                break;
            default:
                $rand = SetRandSting($seed[0],$num);
        }
        return $rand;
    }

    function SetRandSting($rand,$num){
        // 随机数索引最大值
        $max = strlen($rand) - 1;
        $return = '';
        for ($i = 0; $i < $num; $i++) {
            $return .= $rand{mt_rand(0, $max)};
        }
        return $return;
    }
}

if( ! function_exists('getGoodsClass')){
    function getGoodsClass($id,$one=true,$col='gc_name'){
        $model = Model('goods_class');
        $find = $model->field($col)->where(['gc_id'=>$id])->find();
        if($find){
            return $one ? $find[$col] : $find;
        }
        return false;
    }
}

if( ! function_exists('getDeptAdmin')){
    function getDeptAdmin($admin_id,$dept=true){
        $model = Model('hc_vouchers');
        $find = $model->getDeptAdmin($admin_id);
        return $dept ? $find['name'].' '.$find['admin_truename'] : $find['admin_truename'];
    }
}
if( ! function_exists('getUserFind')){
    function getUserFind($user_id,$field='*',$one=true){
        $model = Model('hc_vouchers');
        $find = $model->table('users')->field($field)->where(['id'=>$user_id])->find();
        return $one ? $find[$field] : $find;
    }
}
//代金券来源
if( !function_exists('getVouchersSource')){
    //activated_type
    function getVouchersSource($id){
        $model = Model('hc_vouchers');
        $find = $model->getVoucherFind($id);
        if($find['parent_sn']){
            return '母代金券';
        }else{
            $activatedArr = ['免激活','激活码','平台'];
            return $activatedArr[$find['activated_type']];
        }
    }

    function getVouchersSourceCode($id){
        $model = Model('hc_vouchers');
        $find = $model->getVoucherFind($id);
        if($find['parent_sn']){
            return $find['parent_sn'];
        }else{
            return $find['group_id'];
        }
    }
}

function setHour($hour){
    if(is_numeric($hour)){
        $hour = ($hour>23) ? 23 : $hour;
        return $hour.':00:00';
    }else{
        if(!strstr($hour,':')){
            return '00:00:00';
        }else{
            $hourArr = explode(':',$hour);
            $hours = [];
            $hours[0] = ($hourArr[0]>23) ? 23 : $hourArr[0];
            $hours[1] = ($hourArr[1]>59) ? 59 : $hourArr[1] ;
            if(isset($hourArr[2])) {
                $hours[2] = ($hourArr[0] > 59) ? 59 : $hourArr[2];
            }else{
                $hours[2] = '00';
            }
            return implode(':',$hours);
        }
    }
}

/**
 * 查看投放条数
 */
function getVouchersReleaseTotal($release_id){
    $model = Model('hc_vouchers');
    return $model->getVouchersReleaseTotal($release_id);
}

function showHorve($param,$nav,$other=''){
    $activated = intval( $param['activated']);;
    if($nav ==$param['op'] && $other ==$activated){
        return 'class="current"';
    }
}

function getReleaseUse($id){
    $model = Model('hc_vouchers');
    $table = $model::RELEASE_TABLE;
    $find = $model->table($table)->field('activated_type,release_object,ignore_object')->where(['id'=>$id])->find();
    if(empty($find['activated_type'])){
        return getIgnoreObject($find['ignore_object']);
     }else{
        return $find['release_object']? '推广代理' : '华车内部';
    }
}

function getIgnoreObject($index=0){
    $objArr = ['所有客户','特定客户','一年内未买车的客户','三个月内新注册并且未买车的用户'];
    return $objArr[$index];
}

/**
 * 代金券组的已占条数
 */
function getGroupTake($group_id){
    $model = Model('hc_vouchers');
    $table = $model::RELEASE_TABLE;
    $group = $model::GROUP_TALBE;
    $find = $model->table(implode(',',[$table,$group]))
        ->field("sum({$table}.release_total_num) as take_total")
        ->join('left')
        ->on("{$group}.id={$table}.group_id")
        ->where(["{$table}.group_id"=>$group_id,"{$table}.status"=>['not in',[3,4]],"{$group}.status"=>['neq',2]])->find();
    return is_null($find) ? 0 : $find['take_total'];
}

function isGroupRelease($group_id){
    $model = Model('hc_vouchers');
    $table = $model::RELEASE_TABLE;
    $group = $model::GROUP_TALBE;
    $fields = "sum({$table}.release_total_num) as take_total,{$group}.activated_type,";
    $fields .="({$group}.use_collateral+{$group}.use_sincerity)*{$group}.activated_total_num as jsTotal";
    $find = $model->table(implode(',',[$table,$group]))
        ->field($fields)
        ->join('left')
        ->on("{$group}.id={$table}.group_id")
        ->where(["{$table}.group_id"=>$group_id,"{$table}.status"=>['eq',2],"{$group}.status"=>['neq',2]])
        ->find();
    if($find['take_total'] ==0){
        $result = ['code'=> 0 , 'msg' => '未投放'];
    }else {
        if ($find['activated_type'] == 1) {
            $result = ($find['take_total'] == $find['jsTotal']) ? ['code' => 1, 'msg' => '全部投放'] : ['code' => 2, 'msg' => '部分投放'];
        } else {
            $result = ['code' => 3, 'msg' => '已投放'];
        }
    }
    return $result;
}

function setTotalSplit($total=0,$split=1){
    if(empty($total)){
        return [0,0];
    }
    if($split==1) return [$total,0];
    $splitOne = ceil($total / $split);
    return [$splitOne,$total- $splitOne];
}

function isFreeze($user_id,$value,$col='status',$type='fr'){
    if(is_null($value)){
        return false;
    }

    $map = [
        'value'  => $value,
        'type'   => $type
    ];
    $options['table'] = 'user_freeze';
    $options['field'] = $col;
    $options['where'] = $map;
    $options['order'] = 'id desc';
    $user = Model('user');
    $freeze = $user->getFind($options);
    if($freeze){
        return ($freeze['status'] == 1);
    }
    return false;
}