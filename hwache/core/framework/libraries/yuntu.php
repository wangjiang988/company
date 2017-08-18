<?php
/**
 * 高德云图 SDK
 *
 * 官方详细API说明:http://lbs.amap.com/yuntu/reference/cloudstorage/
 *
 * @author 技安 <php360@qq.com>
 * @link http://www.moqifei.com
 */

defined('InHG') or exit('Access Invalid!');

class yuntu {

    // 秘钥
    private $key = '7d3c0c6f75a818b8fa90669249c83ea6';

    // 数字签名
    private $sig = '46d5ab8a1b3b706a068cfaa01692604c';

    // 数据表 ID
    private $tableid = '551b81a2e4b050797970af88';

    public function __construct($param = array()) {

        if (!empty($param)) {
            // 获取秘钥
            $this->key          = $param['key'];

            // 获取数字签名,如果使用
            if(!empty($param['sig'])) {
                $this->sig      = $param['sig'];
            }

            // 获取表 ID, 新建表则不需要传入此变量
            if(!empty($param['sig'])) {
                $this->tableid  = $param['tableid'];
            }
        }

    }

    /**
     * 创建表
     * @param  string $name  表名称,可以是中文
     * @return               返回结果参数
     */
    public function create($name) {
        // 组合要发送的数据
        $data = array(
            'key'   => $this->key,
            'name'  => $name,
        );
        if(!empty($this->sig)) {
            $data['sig'] = $this->_get_sig($data);
        }

        return $this->curl_post_get(
            'http://yuntuapi.amap.com/datamanage/table/create',
            $data
        );
    }

    /**
     * 添加单条数据
     * @param array $d 新增的数据
     * @return         返回结果参数
     */
    public function add(array $d) {
        // 组合要发送的数据
        $data = array(
            'key'       => $this->key,
            'tableid'   => $this->tableid,
            'loctype'   => 1,
            'data'      => json_encode($d),
        );
        if(!empty($this->sig)) {
            $data['sig'] = $this->_get_sig($data);
        }

        return $this->curl_post_get(
            'http://yuntuapi.amap.com/datamanage/data/create',
            $data
        );
    }

    /**
     * 更新一条数据
     * @param  array  $d 要更新的数据,里面必须包含主键:"_d",主键是作为查询条件
     * @return           是否成功更新参数
     */
    public function update(array $d) {
        // 组合要发送的数据
        $data = array(
            'key'       => $this->key,
            'tableid'   => $this->tableid,
            'loctype'   => 1,
            'data'      => json_encode($d),
        );
        if(!empty($this->sig)) {
            $data['sig'] = $this->_get_sig($data);
        }

        return $this->curl_post_get(
            'http://yuntuapi.amap.com/datamanage/data/update',
            $data
        );
    }

    /**
     * 本地检索
     * @param  array  $data 请求参数数组,详见http://lbs.amap.com/yuntu/reference/cloudsearch/#yuntureference_localsearch
     * @return              返回结果
     */
    public function get_local($data=array()) {
        $url = 'http://yuntuapi.amap.com/datasearch/local?';
        $data['tableid']  = $this->tableid;
        $data['key']      = $this->key;
        if(empty($data['keywords'])) {
            $data['keywords'] = '';
        }
        if(empty($data['city'])) {
            $data['city'] = '全国';
        }
        $url .= http_build_query($data);
        if(!empty($this->sig)) {
            $url .= '&sig='.$this->_get_sig($data);
        }
        return $this->curl_post_get($url, '', 0);
    }

    /**
     * 周边检索
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    public function get_around($data=array()) {
        $url = 'http://yuntuapi.amap.com/datasearch/around?';
        $data['tableid']  = $this->tableid;
        $data['key']      = $this->key;
        if(empty($data['center'])) {
            $data['center'] = '';
        }
        $url .= http_build_query($data);
        if(!empty($this->sig)) {
            $url .= '&sig='.$this->_get_sig($data);
        }
        return $this->curl_post_get($url, '', 0);
    }

    public function get_polygon($location, $radius, $data=array()) {
        $url = 'http://yuntuapi.amap.com/datasearch/polygon?';
    }

    public function get_list($data=array()) {
        $url = 'http://yuntuapi.amap.com/datamanage/data/list?';
        $data['tableid']  = $this->tableid;
        $data['key']      = $this->key;
        $url .= http_build_query($data);
        if(!empty($this->sig)) {
            $url .= '&sig='.$this->_get_sig($data);
        }
        return $this->curl_post_get($url, '', 0);
    }

    /**
     * 数字签名生成
     * @param  string $sig  数字秘钥
     * @param  array  $data 要组合签名的数组
     * @return              生成签名后MD5加密字符串
     */
    private function _get_sig(array $data) {
        $tmp_arr = array();
        foreach($data as $k => $v) {
            $tmp_arr[$k] = $k.'='.$v;
        }
        ksort($tmp_arr, SORT_STRING); // 对数组排序
        $str = implode('&', $tmp_arr) . $this->sig; // 组合数字秘钥
        return md5($str);
    }

    /**
     * post 提交数据
     * @param  string $url  要提交的 url 地址
     * @param  array  $data 要提交的数据参数
     * @return              返回获取到的数据流
     */
    private function curl_post_get($url, $data=array(), $method=1) {
        $ch = curl_init();
        $opt = array(
            CURLOPT_URL             => $url, // 提交的 URL
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_SSL_VERIFYHOST  => false,
            CURLOPT_RETURNTRANSFER  => true, // 将获取的信息以文件流的形式返回，而不是直接输出
        );//return $opt;
        if($method==1) {
            $opt[CURLOPT_POSTFIELDS]= http_build_query($data);
            $opt[CURLOPT_POST]      = true; // post提交
        } else {
            $opt[CURLOPT_HTTPGET]   = true; // get提交
        }
        curl_setopt_array($ch, $opt);
        $r = curl_exec($ch);
        return json_decode($r, true);
    }

}