<?php
/**
 * 函数库
 *
 * @copyright  苏州华车网络科技有限公司
 * @link       http://www.hwache.com
 */

if ( ! function_exists('get_client_ip')) {
    /**
     * 获取客户端IP地址
     * @param integer $type 返回类型 0:返回IP地址 1:返回IPV4地址数字
     * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
     * @return mixed
     */
    function get_client_ip($type = 0, $adv = false)
    {
        $type      = $type ? 1 : 0;
        static $ip = null;
        if ($ip !== null) return $ip[$type];
        if($adv){
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos = array_search('unknown',$arr);
                if(false !== $pos) unset($arr[$pos]);
                $ip = trim($arr[0]);
            } else if (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } else if (isset($_SERVER['REMOTE_ADDR'])) {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        } else if (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u",ip2long($ip));
        $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }
}

if ( ! function_exists('get_day_time')) {
    /**
     * 获取时间戳,以天为单位.即每天的0点
     * @param  $param   时间轴上距离今天的天数，整数是往后，负数是往前。
     * @return array
     */
    function get_day_time($param = null)
    {
        // 一天的秒数
        $oneDaySecond = 24 * 60 * 60;
        // 当前时间戳
        $currentTime = time();
        // 今天0点时间
        $nowDayTime = strtotime(date('Y-m-d'));
        // 今天还剩时间秒数
        $nowDayRemain = $nowDayTime + $oneDaySecond - $currentTime;

        // 返回数组
        $r = [
            'current'   => $currentTime, // 当前时间戳
            'day'       => $nowDayTime, // 今天0点时间戳
            'remain'    => $nowDayRemain, // 今天还剩时间（秒）
        ];
        // 计算指定天得0点
        $r['assign'] = 0;
        if (is_int($param) && $param != 0) {
            $r['assign'] = strtotime(date('Y-m-d', $nowDayTime + $param * $oneDaySecond));
        }
        return $r;
    }
}

if ( ! function_exists('to_guid_string')) {
    /**
     * 根据PHP各种类型变量生成唯一标识号
     * @param mixed $mix 变量
     * @return string
     */
    function to_guid_string($mix)
    {
        if (is_object($mix)) {
            return spl_object_hash($mix);
        } else if (is_resource($mix)) {
            $mix = get_resource_type($mix) . strval($mix);
        } else {
            $mix = serialize($mix);
        }
        return md5($mix);
    }
}

if ( ! function_exists('check_login')) {
    /**
     * 检测会员是否登陆
     * @return bool
     */
    function check_login()
    {
        if (!empty(session('user.is_login'))) {
            return true;
        } else {
            return false;
        }
    }
}
if ( ! function_exists('check_login_agents')) {
    /**
     * 检测经销商代理是否登陆
     * @return bool
     */
    function check_login_agents()
    {
        if (!empty($_SESSION['is_login_agents'])) {
            return true;
        } else {
            return false;
        }
    }
}
if ( ! function_exists('check_login_admin')) {
    /**
     * 检测管理是否登陆
     * @return bool
     */
    function check_login_admin()
    {
        if (!empty($_SESSION['is_login_admin'])) {
            return true;
        } else {
            return false;
        }
    }
}
if ( ! function_exists('sess')) {
    /**
     * session管理函数
     * @param string|array $name session名称 如果为数组则表示进行session设置
     * @param mixed $value session值
     * @return mixed
     */
    function sess($name='', $value='')
    {
        $prefix   = config('session.prefix');

        if('' === $value) {
            if(''===$name) {
                // 获取全部的session
                return $prefix ? $_SESSION[$prefix] : $_SESSION;
            } else if (0===strpos($name,'[')) { // session 操作
                if('[pause]'==$name) { // 暂停session
                    session_write_close();
                } else if ('[start]'==$name) { // 启动session
                    session_start();
                } else if ('[destroy]'==$name) { // 销毁session
                    $_SESSION =  array();
                    session_unset();
                    session_destroy();
                } else if ('[regenerate]'==$name) { // 重新生成id
                    session_regenerate_id();
                }
            } else if (0===strpos($name,'?')) { // 检查session
                $name = substr($name,1);
                if (strpos($name,'.')) { // 支持数组
                    list($name1,$name2) = explode('.',$name);
                    return $prefix
                        ? isset($_SESSION[$prefix][$name1][$name2])
                        : isset($_SESSION[$name1][$name2]);
                } else {
                    return $prefix
                        ? isset($_SESSION[$prefix][$name])
                        : isset($_SESSION[$name]);
                }
            } else if (is_null($name)) { // 清空session
                if ($prefix) {
                    unset($_SESSION[$prefix]);
                } else {
                    $_SESSION = array();
                }
            } else if ($prefix) { // 获取session
                if (strpos($name,'.')) {
                    list($name1,$name2) = explode('.',$name);
                    return isset($_SESSION[$prefix][$name1][$name2])
                        ? $_SESSION[$prefix][$name1][$name2]
                        : null;
                } else {
                    return isset($_SESSION[$prefix][$name])
                        ? $_SESSION[$prefix][$name]
                        : null;
                }
            } else {
                if (strpos($name,'.')) {
                    list($name1,$name2) = explode('.',$name);
                    return isset($_SESSION[$name1][$name2])
                        ? $_SESSION[$name1][$name2]
                        : null;
                } else {
                    return isset($_SESSION[$name])
                        ? $_SESSION[$name]
                        : null;
                }
            }
        } else if (is_null($value)) { // 删除session
            if (strpos($name,'.')) {
                list($name1,$name2) = explode('.',$name);
                if($prefix){
                    unset($_SESSION[$prefix][$name1][$name2]);
                } else {
                    unset($_SESSION[$name1][$name2]);
                }
            } else {
                if($prefix){
                    unset($_SESSION[$prefix][$name]);
                } else {
                    unset($_SESSION[$name]);
                }
            }
        } else { // 设置session
            if (strpos($name,'.')) {
                list($name1,$name2) = explode('.',$name);
                if ($prefix) {
                    $_SESSION[$prefix][$name1][$name2] = $value;
                } else {
                    $_SESSION[$name1][$name2] = $value;
                }
            } else {
                if ($prefix) {
                    $_SESSION[$prefix][$name] = $value;
                } else {
                    $_SESSION[$name] = $value;
                }
            }
        }
        return null;
    }
}

if ( ! function_exists('rebuild_token')) {
    /**
     * 重新生成token
     */
    function rebuild_token()
    {
        Session::put('_token', str_random(40));
    }
}

if ( ! function_exists('generate_sn')) {
    /**
     * 生成订单号
     */
    function generate_sn()
    {
        list($usec, $sec) = explode(' ', microtime());
        $usec = substr(str_replace('0.', '', $usec), 0, 4);
        $str  = rand(10, 99);

        return date("YmdHis", $sec).$usec.$str;
    }
}

if (! function_exists('get_order_status')) {
    /**
     * 通过订单数字状态码返回订单字符码
     * @param int $code
     * @return mixed
     */
    function get_order_status($code)
    {
        $status = array_flip($_ENV['_CONF']['config']['hg_order']);

        return $status[$code];
    }
}

if (! function_exists('get_order_code')) {
    /**
     * 通过订单字符状态码返回订单状态数字码
     * @param $str
     * @return mixed
     */
    function get_order_code($str)
    {
        return $_ENV['_CONF']['config']['hg_order'][$str];
    }
}

if ( ! function_exists('is_mobile')) {
    /**
     * 检测是否是手机端
     */
    function is_mobile()
    {
        //
    }
}

if ( ! function_exists('GetIpLookup')) {
    // 取得ip对应的城市
    function GetIpLookup($ip = ''){
        if(empty($ip)){
            $ip = '180.106.77.23';
        }
        $res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=' . $ip);
        if(empty($res)){ return false; }

        return json_decode($res, true);
    }
}
// 计算价格时，保留两位小数，后面几位小数如果大于0不舍只入
if(! function_exists('GetMyprice')){
    function GetMyprice($price=0){
        $price2=sprintf("%.2f",$price);
        if(($price-$price2)>0){
            $price+=0.01;
        }
        return sprintf("%.2f",$price);
    }

}
/**
    *数字金额转换成中文大写金额的函数
    *String Int  $num  要转换的小写数字或小写字符串
    *return 大写字母
    *小数位为两位
    **/
if (! function_exists('num_to_rmb')) {
    function num_to_rmb($num){
            $c1 = "零壹贰叁肆伍陆柒捌玖";
            $c2 = "分角元拾佰仟万拾佰仟亿";
            //精确到分后面就不要了，所以只留两个小数位
            $num = round($num, 2);
            //将数字转化为整数
            $num = $num * 100;
            if (strlen($num) > 10) {
                    return "金额太大，请检查";
            }
            $i = 0;
            $c = "";
            while (1) {
                    if ($i == 0) {
                            //获取最后一位数字
                            $n = substr($num, strlen($num)-1, 1);
                    } else {
                            $n = $num % 10;
                    }
                    //每次将最后一位数字转化为中文
                    $p1 = substr($c1, 3 * $n, 3);
                    $p2 = substr($c2, 3 * $i, 3);
                    if ($n != '0' || ($n == '0' && ($p2 == '亿' || $p2 == '万' || $p2 == '元'))) {
                            $c = $p1 . $p2 . $c;
                    } else {
                            $c = $p1 . $c;
                    }
                    $i = $i + 1;
                    //去掉数字最后一位了
                    $num = $num / 10;
                    $num = (int)$num;
                    //结束循环
                    if ($num == 0) {
                            break;
                    }
            }
            $j = 0;
            $slen = strlen($c);
            while ($j < $slen) {
                    //utf8一个汉字相当3个字符
                    $m = substr($c, $j, 6);
                    //处理数字中很多0的情况,每次循环去掉一个汉字“零”
                    if ($m == '零元' || $m == '零万' || $m == '零亿' || $m == '零零') {
                            $left = substr($c, 0, $j);
                            $right = substr($c, $j + 3);
                            $c = $left . $right;
                            $j = $j-3;
                            $slen = $slen-3;
                    }
                    $j = $j + 3;
            }
            //这个是为了去掉类似23.0中最后一个“零”字
            if (substr($c, strlen($c)-3, 3) == '零') {
                    $c = substr($c, 0, strlen($c)-3);
            }
            //将处理的汉字加上“整”
            if (empty($c)) {
                    return "零元整";
            }else{
                    return $c . "整";
            }
    }
    /*取得性别的文字说明*/
    if (! function_exists('getSex')) {
        function getSex($sex){
            switch ($sex) {
                case '0':
                    return '先生';
                    break;
                case '1':
                    return '女士';
                break;
                default:
                    return '保密';
                    break;
            }

        }

    }
}
// 使用，将字符串分割成数组
if (!function_exists('dexplode')) {
    function dexplode($str)
    {
        return explode(',',str_replace('，',',',$str));
    }
}
if (!function_exists('ddate')) {
    // 格式化日期
    function ddate($date,$type=1)
    {
        switch ($type) {
            case 1:
                return date('Y年m月d日',strtotime($date));
                break;
            case 2:
                return date('Y-m-d',strtotime($date));
                break;
            case 3:
                return date('Y-m-d H:i:s',strtotime($date));
                break;
            case 4:
                return date('m-d',strtotime($date));
                break;
            default:
                return date('Y年m月d日 H:i:s',strtotime($date));
                break;
        }
    }

}
if (!function_exists('getStatusNotice')) {
    // 取得订单状态的文字说明
    function getStatusNotice($status)
    {
        $notice='';
        $orderstatus=Config::get('orderstatus');
        foreach ($orderstatus as $key => $value) {
            if ($status==$key) {
                $notice=$value['notice'];
                break;
            }
        }
        return $notice;
    }

}
if (!function_exists('formatNum')) {
    // 格式化输出编号
    function formatNum($num,$type=1)
    {
        switch ($type) {
            case '1':
            //用户
                $format= 'HCU%08d';
                break;
            case '2':
            // 经销商代理
                $format= 'HCS%08d';
                break;
            case '3':
            // 经销商
                $format= 'HCD%08d';
                break;
            default:
                $format= 'HCU%08d';
                break;
        }

        return sprintf($format, $num);
    }
}
// 取得车辆保险的类型,$typeid 购车类别企业还是个人，$seat 座位数
if (!function_exists('getBaoxianType')) {
    function getBaoxianType($typeid,$seat)
    {
        if ($typeid==2) {
            // 个人用车
            return $seat>=6?2:1;
        }else{
            // 公司用车
            return $seat>=6?4:3;
        }
    }
}
/*
*当前时间距某个日期多少天
*$type=1 返回天数
*type=2  为负数则返回0,返回分：秒
*type=3  返回过去某个时间距离现在的分钟数
*其他返回 天：时： 分： 秒
*/

if (!function_exists('diffBetweenTwoDays')) {
    function diffBetweenTwoDays ($day,$type=1)
    {
          $second1 = time();
          $second2 = strtotime($day);
          switch ($type) {
            case '1':
                  if ($second1 > $second2) {
                    return 0;
                  }else{
                    return intval(($second2 - $second1) / 86400);
                  }

                  break;
            case '2':
                  if ($second1>$second2) {
                      return 0;
                  }else{
                    $timediff = $second2 - $second1;
                      $days = intval( $timediff / 86400 );
                      $remain = $timediff % 86400;
                      $hours = intval( $remain / 3600 );
                      $remain = $remain % 3600;
                      $mins = intval( $remain / 60 );
                      $secs = $remain % 60;
                    return $mins.'分钟'.$secs.'秒' ;
                  }
                  break;
            case '3':
                    if ($second1>=$second2) {
                        return 0;
                    }else{
                      return intval(($second1 - $second2) / 60);
                    }
                    break;
            case '4':
                  if ($second1 < $second2) {
                    $tmp = $second2;
                    $second2 = $second1;
                    $second1 = $tmp;
                  }
                  $timediff = $second1 - $second2;
                  $days = intval( $timediff / 86400 );
                  $remain = $timediff % 86400;
                  $hours = intval( $remain / 3600 );
                  $remain = $remain % 3600;
                  $mins = intval( $remain / 60 );
                  $secs = $remain % 60;
                  return $days.'天 '.$hours.'小时 '.$mins.'分钟'.$secs.'秒' ;
                break;
            case '5':
                if ($second1>=$second2) {
                    return 0;
                }else{
                  $timediff = $second2 - $second1;
                  $days = intval( $timediff / 86400 );
                  $remain = $timediff % 86400;
                  $hours = intval( $remain / 3600 );
                  $remain = $remain % 3600;
                  $mins = intval( $remain / 60 );
                  $secs = $remain % 60;
                  return $hours.'小时 '.$mins.'分钟'.$secs.'秒' ;
                }
                break;
          }
    }
}

// 过滤字符串中的空格，换行符
if (!function_exists('trimall')) {
    function trimall($str)
    {
        $qian=array(" ","　","\t","\n","\r");
        return str_replace($qian, '', $str);
    }
}
// 手机号码中间用*代替
if (!function_exists('changeMobile')) {
    function changeMobile($mobile)
    {
        return substr_replace($mobile,'****',3,4);
    }
}
// 将汉字转成拼音
if (!function_exists('Pinyin')) {
    function Pinyin($_String, $_Code='gb2312')
    {
        $_DataKey = "a|ai|an|ang|ao|ba|bai|ban|bang|bao|bei|ben|beng|bi|bian|biao|bie|bin|bing|bo|bu|ca|cai|can|cang|cao|ce|ceng|cha".
            "|chai|chan|chang|chao|che|chen|cheng|chi|chong|chou|chu|chuai|chuan|chuang|chui|chun|chuo|ci|cong|cou|cu|".
            "cuan|cui|cun|cuo|da|dai|dan|dang|dao|de|deng|di|dian|diao|die|ding|diu|dong|dou|du|duan|dui|dun|duo|e|en|er".
            "|fa|fan|fang|fei|fen|feng|fo|fou|fu|ga|gai|gan|gang|gao|ge|gei|gen|geng|gong|gou|gu|gua|guai|guan|guang|gui".
            "|gun|guo|ha|hai|han|hang|hao|he|hei|hen|heng|hong|hou|hu|hua|huai|huan|huang|hui|hun|huo|ji|jia|jian|jiang".
            "|jiao|jie|jin|jing|jiong|jiu|ju|juan|jue|jun|ka|kai|kan|kang|kao|ke|ken|keng|kong|kou|ku|kua|kuai|kuan|kuang".
            "|kui|kun|kuo|la|lai|lan|lang|lao|le|lei|leng|li|lia|lian|liang|liao|lie|lin|ling|liu|long|lou|lu|lv|luan|lue".
            "|lun|luo|ma|mai|man|mang|mao|me|mei|men|meng|mi|mian|miao|mie|min|ming|miu|mo|mou|mu|na|nai|nan|nang|nao|ne".
            "|nei|nen|neng|ni|nian|niang|niao|nie|nin|ning|niu|nong|nu|nv|nuan|nue|nuo|o|ou|pa|pai|pan|pang|pao|pei|pen".
            "|peng|pi|pian|piao|pie|pin|ping|po|pu|qi|qia|qian|qiang|qiao|qie|qin|qing|qiong|qiu|qu|quan|que|qun|ran|rang".
            "|rao|re|ren|reng|ri|rong|rou|ru|ruan|rui|run|ruo|sa|sai|san|sang|sao|se|sen|seng|sha|shai|shan|shang|shao|".
            "she|shen|sheng|shi|shou|shu|shua|shuai|shuan|shuang|shui|shun|shuo|si|song|sou|su|suan|sui|sun|suo|ta|tai|".
            "tan|tang|tao|te|teng|ti|tian|tiao|tie|ting|tong|tou|tu|tuan|tui|tun|tuo|wa|wai|wan|wang|wei|wen|weng|wo|wu".
            "|xi|xia|xian|xiang|xiao|xie|xin|xing|xiong|xiu|xu|xuan|xue|xun|ya|yan|yang|yao|ye|yi|yin|ying|yo|yong|you".
            "|yu|yuan|yue|yun|za|zai|zan|zang|zao|ze|zei|zen|zeng|zha|zhai|zhan|zhang|zhao|zhe|zhen|zheng|zhi|zhong|".
            "zhou|zhu|zhua|zhuai|zhuan|zhuang|zhui|zhun|zhuo|zi|zong|zou|zu|zuan|zui|zun|zuo";
        $_DataValue = "-20319|-20317|-20304|-20295|-20292|-20283|-20265|-20257|-20242|-20230|-20051|-20036|-20032|-20026|-20002|-19990".
            "|-19986|-19982|-19976|-19805|-19784|-19775|-19774|-19763|-19756|-19751|-19746|-19741|-19739|-19728|-19725".
            "|-19715|-19540|-19531|-19525|-19515|-19500|-19484|-19479|-19467|-19289|-19288|-19281|-19275|-19270|-19263".
            "|-19261|-19249|-19243|-19242|-19238|-19235|-19227|-19224|-19218|-19212|-19038|-19023|-19018|-19006|-19003".
            "|-18996|-18977|-18961|-18952|-18783|-18774|-18773|-18763|-18756|-18741|-18735|-18731|-18722|-18710|-18697".
            "|-18696|-18526|-18518|-18501|-18490|-18478|-18463|-18448|-18447|-18446|-18239|-18237|-18231|-18220|-18211".
            "|-18201|-18184|-18183|-18181|-18012|-17997|-17988|-17970|-17964|-17961|-17950|-17947|-17931|-17928|-17922".
            "|-17759|-17752|-17733|-17730|-17721|-17703|-17701|-17697|-17692|-17683|-17676|-17496|-17487|-17482|-17468".
            "|-17454|-17433|-17427|-17417|-17202|-17185|-16983|-16970|-16942|-16915|-16733|-16708|-16706|-16689|-16664".
            "|-16657|-16647|-16474|-16470|-16465|-16459|-16452|-16448|-16433|-16429|-16427|-16423|-16419|-16412|-16407".
            "|-16403|-16401|-16393|-16220|-16216|-16212|-16205|-16202|-16187|-16180|-16171|-16169|-16158|-16155|-15959".
            "|-15958|-15944|-15933|-15920|-15915|-15903|-15889|-15878|-15707|-15701|-15681|-15667|-15661|-15659|-15652".
            "|-15640|-15631|-15625|-15454|-15448|-15436|-15435|-15419|-15416|-15408|-15394|-15385|-15377|-15375|-15369".
            "|-15363|-15362|-15183|-15180|-15165|-15158|-15153|-15150|-15149|-15144|-15143|-15141|-15140|-15139|-15128".
            "|-15121|-15119|-15117|-15110|-15109|-14941|-14937|-14933|-14930|-14929|-14928|-14926|-14922|-14921|-14914".
            "|-14908|-14902|-14894|-14889|-14882|-14873|-14871|-14857|-14678|-14674|-14670|-14668|-14663|-14654|-14645".
            "|-14630|-14594|-14429|-14407|-14399|-14384|-14379|-14368|-14355|-14353|-14345|-14170|-14159|-14151|-14149".
            "|-14145|-14140|-14137|-14135|-14125|-14123|-14122|-14112|-14109|-14099|-14097|-14094|-14092|-14090|-14087".
            "|-14083|-13917|-13914|-13910|-13907|-13906|-13905|-13896|-13894|-13878|-13870|-13859|-13847|-13831|-13658".
            "|-13611|-13601|-13406|-13404|-13400|-13398|-13395|-13391|-13387|-13383|-13367|-13359|-13356|-13343|-13340".
            "|-13329|-13326|-13318|-13147|-13138|-13120|-13107|-13096|-13095|-13091|-13076|-13068|-13063|-13060|-12888".
            "|-12875|-12871|-12860|-12858|-12852|-12849|-12838|-12831|-12829|-12812|-12802|-12607|-12597|-12594|-12585".
            "|-12556|-12359|-12346|-12320|-12300|-12120|-12099|-12089|-12074|-12067|-12058|-12039|-11867|-11861|-11847".
            "|-11831|-11798|-11781|-11604|-11589|-11536|-11358|-11340|-11339|-11324|-11303|-11097|-11077|-11067|-11055".
            "|-11052|-11045|-11041|-11038|-11024|-11020|-11019|-11018|-11014|-10838|-10832|-10815|-10800|-10790|-10780".
            "|-10764|-10587|-10544|-10533|-10519|-10331|-10329|-10328|-10322|-10315|-10309|-10307|-10296|-10281|-10274".
            "|-10270|-10262|-10260|-10256|-10254";
        $_TDataKey   = explode('|', $_DataKey);
        $_TDataValue = explode('|', $_DataValue);
        $_Data = array_combine($_TDataKey,  $_TDataValue);
        arsort($_Data);
        reset($_Data);
        if($_Code!= 'gb2312') $_String = _U2_Utf8_Gb($_String);
        $_Res = '';
        for($i=0; $i<strlen($_String); $i++)  {
                $_P = ord(substr($_String, $i, 1));
                if($_P>160) {
                        $_Q = ord(substr($_String, ++$i, 1)); $_P = $_P*256 + $_Q - 65536;
                }
                $_Res .= _Pinyin($_P, $_Data);
        }
        return preg_replace("/[^a-z0-9]*/", '', $_Res);
    }
}
if (!function_exists('_Pinyin')) {
    function _Pinyin($_Num, $_Data){
            if($_Num>0 && $_Num<160 ){
                    return chr($_Num);
            }elseif($_Num<-20319 || $_Num>-10247){
                    return '';
            }else{
                    foreach($_Data as $k=>$v){ if($v<=$_Num) break; }
                    return $k;
            }
    }
}
if (!function_exists('_U2_Utf8_Gb')) {
    function _U2_Utf8_Gb($_C){
        $_String = '';
        if($_C < 0x80){
                $_String .= $_C;
        }elseif($_C < 0x800)  {
                $_String .= chr(0xC0 | $_C>>6);
                $_String .= chr(0x80 | $_C & 0x3F);
        }elseif($_C < 0x10000){
                $_String .= chr(0xE0 | $_C>>12);
                $_String .= chr(0x80 | $_C>>6 & 0x3F);
                $_String .= chr(0x80 | $_C & 0x3F);
        }elseif($_C < 0x200000) {
                $_String .= chr(0xF0 | $_C>>18);
                $_String .= chr(0x80 | $_C>>12 & 0x3F);
                $_String .= chr(0x80 | $_C>>6 & 0x3F);
                $_String .= chr(0x80 | $_C & 0x3F);
        }
        return iconv('UTF-8', 'GB2312', $_String);
    }
}
// 车辆用途
if(!function_exists('carUse')){
    function carUse($id)
    {
        return $id==2?'非营运个人用车' :'非营运公司用车';
    }

}
// 将字符串转为数组
if (!function_exists('mbStrSplit')) {
    function mbStrSplit($string, $len=1)
    {
        $start = 0;
          $strlen = mb_strlen($string);
          while ($strlen) {
            $array[] = mb_substr($string,$start,$len,"utf8");
            $string = mb_substr($string, $len, $strlen,"utf8");
            $strlen = mb_strlen($string);
          }
          return $array;

    }
}
//  26个大写英文字母
if (!function_exists('showLetter')) {
    function showLetter()
    {
        return array('A' ,'B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z' );
    }
}
// 各省缩写
if (!function_exists('getSheng')) {
    function getSheng()
    {
        return array('京','津','冀','晋','蒙','辽','吉','黑','沪','苏','浙','皖','闽','赣','鲁','豫','鄂','湘','粤','桂','琼','渝','川','贵','云','藏','陕','甘','青','宁','新','港','澳','台');
    }
}
// 付款方式文字说明
if (!function_exists('payType')) {
    function payType($id)
    {
        return $id==1?'全款':'贷款' ;
    }
}
// 订单首次确定的上牌方式
if (!function_exists('getShangpai')) {
    function getShangpai($id)
    {
        switch (intval($id)) {
            case '1':
                return '确定代办';
                break;
            case '2':
                return '不代办';
                break;
            case '3':
                return '待定';//实际指定上牌
                break;
            case '4':
                return '待定';//实际本人上牌
                break;
            default:
                return '自由上牌';
                break;
        }
    }
}
// 是否安装文字说明
if (!function_exists('getInstall')) {
    function getInstall($id)
    {
        return $id==1?'已安装':'未安装';
    }
}

if (!function_exists('get_rand')) {
    /**
     * 生成随机数
     *
     * @param int $type 生成随机数池的种类
     * @param int $num  随机数数量
     * @return string
     */
    function get_rand($type = 1, $num = 4)
    {
        // 随机数种子
        $seed = [
            '1234567890',
            'abcdefghijklmnopqrstuvwxyz',
            'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
            '~!@#$%^&*()_+[];:<>?/,.|'
        ];

        // 根据不同的类别生成随机数池
        switch ($type) {
            case 2: // 数字，小写字母
                $rand = $seed[0].$seed[1];
                break;
            case 3: // 数字，小写字母，大写字母
                $rand = $seed[0].$seed[1].$seed[2];
                break;
            case 4: // 数字，小写字母，大写字母，特殊字符
                $rand = $seed[0].$seed[1].$seed[2].$seed[3];
                break;
            default:
                $rand = $seed[0];
        }
        // 随机数索引最大值
        $max = strlen($rand) - 1;

        $return = '';
        for ($i = 0; $i < $num; $i++) {
            $return .= $rand{mt_rand(0, $max)};
        }

        return $return;
    }
}
// 允许的文件扩展名
if (!function_exists('allowext')) {
    function allowext($ext)
    {
        $arr = array('jpg','gif','bmp','jpeg','mp3','mp4','wmv' );
        return in_array(strtolower($ext),$arr);
    }
}
// 去除数组中某项,$arr1,源数组，$ids要去除的id
if (!function_exists('unsetArray')) {
    function unsetArray($arr1,$ids)
    {
        if(!is_array($arr1) || !is_array($ids)) return false;
        foreach ($arr1 as $key => $value) {
            if(in_array($value['id'], $ids)) unset($arr1[$key]);
        }
        return $arr1;
    }
}

if (!function_exists('get_serial_id')) {
    /**
     * 生成唯一序列号
     * 日期(8)+机器编号(<=5)+用户id(<=9)+商家id(<=9)+增长码(<=5),最大36字符
     * 用户和商家的id为负数，则取绝对值并前面添加0
     * 增长码是每天序列号的自然增长，自动加1
     *
     * @param $userId
     * @param $dealerId
     * @return string
     */
    function get_serial_id($userId, $dealerId)
    {
        // 日期
        $time = date('Ymd');
        // 机器码
        $machineId = env('MACHINE_ID');
        //用户id
        if ($userId < 0) {
            $userId = '0'.abs($userId);
        }
        // 商家id
        if ($dealerId < 0) {
            $dealerId = '0'.abs($dealerId);
        }
        // 生成自增码
        $key = 'counter';
        if (!\Redisp::exists($key)) {
            $expireTime = strtotime($time)+86400;
            \Redisp::set($key, 0);
            \Redisp::expireat($key, $expireTime);
        }
        $code = \Redisp::incr($key);

        return $time.$machineId.$userId.$dealerId.$code;
    }
}

if (!function_exists('makeSeccode')) {
/**
 * 产生验证码
 *
 * @param string $nchash 哈希数
 * @return string
 */
	function makeSeccode(){
		$seccode = random(6, 1);
		$seccodeunits = '';
		
		$s = sprintf('%04s', base_convert($seccode, 10, 23));
		$seccodeunits = 'ABCEFGHJKMPRTVXY2346789';
		if($seccodeunits) {
			$seccode = '';
			for($i = 0; $i < 4; $i++) {
				$unit = ord($s{$i});
				$seccode .= ($unit >= 0x30 && $unit <= 0x39) ? $seccodeunits[$unit - 0x30] : $seccodeunits[$unit - 0x57];
			}
		}
		return $seccode;
	}
}
if (!function_exists('checkSeccode')) {
/**
 * 验证验证码
 *
 * @param string $nchash 哈希数
 * @param string $value 待验证值
 * @return boolean
 */
	function checkSeccode($value){
		if(strtolower(session('user_code'))==strtolower($value)){
			return true;
		}else{
			return false;
		}
	}
}
if (!function_exists('random')) {
/**
 * 取得随机数
 *
 * @param int $length 生成随机数的长度
 * @param int $numeric 是否只产生数字随机数 1是0否
 * @return string
 */
	function random($length, $numeric = 0) {
		$seed = base_convert(md5(microtime().$_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
		$seed = $numeric ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));
		$hash = '';
		$max = strlen($seed) - 1;
		for($i = 0; $i < $length; $i++) {
			$hash .= $seed{mt_rand(0, $max)};
		}
		return $hash;
	}
}

if (!function_exists('encrypt')) {
/**
 * 加密函数
 *
 * @param string $txt 需要加密的字符串
 * @param string $key 密钥
 * @return string 返回加密结果
 */
	function encrypt($txt, $key = ''){
		if (empty($txt)) return $txt;
		if (empty($key)) $key = md5(MD5_KEY);
		$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_.";
		$ikey ="-x6g6ZWm2G9g_vr0Bo.pOq3kRIxsZ6rm";
		$nh1 = rand(0,64);
		$nh2 = rand(0,64);
		$nh3 = rand(0,64);
		$ch1 = $chars{$nh1};
		$ch2 = $chars{$nh2};
		$ch3 = $chars{$nh3};
		$nhnum = $nh1 + $nh2 + $nh3;
		$knum = 0;$i = 0;
		while(isset($key{$i})) $knum +=ord($key{$i++});
		$mdKey = substr(md5(md5(md5($key.$ch1).$ch2.$ikey).$ch3),$nhnum%8,$knum%8 + 16);
		$txt = base64_encode(time().'_'.$txt);
		$txt = str_replace(array('+','/','='),array('-','_','.'),$txt);
		$tmp = '';
		$j=0;$k = 0;
		$tlen = strlen($txt);
		$klen = strlen($mdKey);
		for ($i=0; $i<$tlen; $i++) {
			$k = $k == $klen ? 0 : $k;
			$j = ($nhnum+strpos($chars,$txt{$i})+ord($mdKey{$k++}))%64;
			$tmp .= $chars{$j};
		}
		$tmplen = strlen($tmp);
		$tmp = substr_replace($tmp,$ch3,$nh2 % ++$tmplen,0);
		$tmp = substr_replace($tmp,$ch2,$nh1 % ++$tmplen,0);
		$tmp = substr_replace($tmp,$ch1,$knum % ++$tmplen,0);
		return $tmp;
	}
}
if (!function_exists('decrypt')) {
/**
 * 解密函数
 *
 * @param string $txt 需要解密的字符串
 * @param string $key 密匙
 * @return string 字符串类型的返回结果
 */
	function decrypt($txt, $key = '', $ttl = 0){
		if (empty($txt)) return $txt;
		if (empty($key)) $key = md5(MD5_KEY);
	
		$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_.";
		$ikey ="-x6g6ZWm2G9g_vr0Bo.pOq3kRIxsZ6rm";
		$knum = 0;$i = 0;
		$tlen = @strlen($txt);
		while(isset($key{$i})) $knum +=ord($key{$i++});
		$ch1 = @$txt{$knum % $tlen};
		$nh1 = strpos($chars,$ch1);
		$txt = @substr_replace($txt,'',$knum % $tlen--,1);
		$ch2 = @$txt{$nh1 % $tlen};
		$nh2 = @strpos($chars,$ch2);
		$txt = @substr_replace($txt,'',$nh1 % $tlen--,1);
		$ch3 = @$txt{$nh2 % $tlen};
		$nh3 = @strpos($chars,$ch3);
		$txt = @substr_replace($txt,'',$nh2 % $tlen--,1);
		$nhnum = $nh1 + $nh2 + $nh3;
		$mdKey = substr(md5(md5(md5($key.$ch1).$ch2.$ikey).$ch3),$nhnum % 8,$knum % 8 + 16);
		$tmp = '';
		$j=0; $k = 0;
		$tlen = @strlen($txt);
		$klen = @strlen($mdKey);
		for ($i=0; $i<$tlen; $i++) {
			$k = $k == $klen ? 0 : $k;
			$j = strpos($chars,$txt{$i})-$nhnum - ord($mdKey{$k++});
			while ($j<0) $j+=64;
			$tmp .= $chars{$j};
		}
		$tmp = str_replace(array('-','_','.'),array('+','/','='),$tmp);
		$tmp = trim(base64_decode($tmp));
	
		if (preg_match("/\d{10}_/s",substr($tmp,0,11))){
			if ($ttl > 0 && (time() - substr($tmp,0,11) > $ttl)){
				$tmp = null;
			}else{
				$tmp = substr($tmp,11);
			}
		}
		return $tmp;
	}
}
if (!function_exists('getAreaId')) {
	/**
	 * 根据 省和市的字符串  判断省和地市的ID
	 * @param unknown $provinceName
	 * @param unknown $city_name
	 * @return multitype:number string |multitype:number string mixed |multitype:number mixed
	 */
	function getAreaId($provinceName,$city_name){
		$area = config('area');
		$provinceArray = array();
		foreach($area[0] as $k =>$v){
			$provinceArray[$k] = preg_replace('/(省|市)/i','',$v['name']);
		}
		$province = array_search($provinceName, $provinceArray);
		if($province==false){
			return array('error_code'=>1,'province'=>'','city'=>'');
		}
		$cityArray = array();
		foreach($area[$province] as $k=>$v){
			$cityArray[$k] = preg_replace('/市/i','',$v['name']);
		}
		$city = array_search($city_name, $cityArray);
		if($city==false){
			return array('error_code'=>1,'province'=>$province,'city'=>'');
		}else{
			return array('error_code'=>0,'province'=>$province,'city'=>$city);
		}
	}
}

if ( ! function_exists('check_job_time')) {
    /**
     * 检测是否在工作时间
     * @return bool
     */
    function check_job_time()
    {
        $hour = date('H',time());
        return (($hour >=9 && $hour <= 11) || ($hour >=13 && $hour <= 17));
    }
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