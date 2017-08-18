<?php
/**
 * 路由
 *
 */
defined('InHG') or exit('Access Invalid!');

class Route {

    /**
     * PATH_INFO 分隔符
     *
     * @var string
     */
    private $_pathinfo_split = '-';

    /**
     * 系统配置信息
     *
     * @var array
     */

    private $_config = array();

    /**
     * PATH_INFO内容分隔正则
     *
     * @var string
     */
    private $_pathinfo_pattern = '';

    /**
     * 伪静态文件扩展名
     *
     * @var string
     */
    private $_rewrite_extname = '.html';

    /**
     * 构造方法
     *
     */
    public function __construct($config = array()) {
        $this->_config = $config;
        $this->_pathinfo_pattern = "/{$this->_pathinfo_split}/";
        $this->parseRule();
    }

    /**
     * 路由解析
     *
     */
    public function parseRule() {
        if ($this->_config['url_model']) {
            $this->_parseRuleRewrite();
        } else {
            $this->_parseRuleNormal();
        }
    }

    /**
     * 默认URL模式
     *
     */
    private function _parseRuleNormal() {
        //不进行任何处理
    }

    /**
     * 伪静态模式
     *
     */
    private function _parseRuleRewrite() {
        $path_info = !empty($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : $_SERVER['REDIRECT_REDIRECT_PATH_INFO'];
        $path_info = trim($path_info,'/');

        //如果为二级目录去掉目录信息
        if(strpos($path_info, '/')) {
            $path_info_array = explode('/', $path_info);
            $path_info = end($path_info_array);
        }

        if (!empty($path_info) && $path_info != 'index.php' && strpos($path_info, $this->_rewrite_extname)){
            //去掉伪静态扩展名
            $path_info = substr($path_info,0,-strlen($this->_rewrite_extname));

            //根据不同APP匹配url规则
            $path_info_function = '_' . APP_ID . 'PathInfo';
            $path_info = $this->$path_info_function($path_info);

            $split_array = preg_split($this->_pathinfo_pattern,$path_info);
            //act,op强制赋值
            $_GET['act'] = isset($split_array[0]) ? $split_array[0] : 'index';
            $_GET['op'] = isset($split_array[1]) ? $split_array[1] : 'index';
            unset($split_array[0]);
            unset($split_array[1]);

            //其它参数也放入$_GET
            while (current($split_array) !== false) {
                $name = current($split_array);
                $value = next($split_array);
                $_GET[$name] = $value;
                if (next($split_array) === false){
                    break;
                }
            }
        } else {
            $_GET['act'] = $_GET['op'] = 'index';
        }
    }

    /**
     * 商城短网址还原成长网址
     * @param unknown $path_info
     * @return mixed
     */
    private function _shopPathInfo($path_info) {
        $reg_match_from = array(
            '/^item-(\d+)$/',
            '/^shop-(\d+)$/',
            '/^shop_view-(\d+)-(\d+)-([0-5])-([0-2])-(\d+)$/',
            '/^article-(\d+)$/',
            '/^article_cate-(\d+)$/',
            '/^document-([a-z_]+)$/',
            '/^cate-(\d+)-([0-9_]+)-([0-9_]+)-([0-3])-([0-2])-([0-2])-(\d+)-(\d+)$/',
            '/^brand-(\d+)-([0-3])-([0-2])-([0-2])-(\d+)-(\d+)$/',
            '/^brand$/',
            '/^groupbuy-(\d+)-(\d+)-(\d+)-(\d+)-(\d+)-(\d+)$/',
            '/^groupbuy_soon-(\d+)-(\d+)-(\d+)-(\d+)-(\d+)-(\d+)$/',
            '/^groupbuy_history-(\d+)-(\d+)-(\d+)-(\d+)-(\d+)-(\d+)$/',
            '/^groupbuy_detail-(\d+)$/',
            '/^integral$/',
            '/^integral_list$/',
            '/^integral_item-(\d+)$/',
            '/^voucher$/',
            '/^comments-(\d+)-([0-3])-(\d+)$/'
        );
        $reg_match_to = array(
            'goods-index-goods_id-\\1',
            'show_store-index-store_id-\\1',
            'show_store-goods_all-store_id-\\1-stc_id-\\2-key-\\3-order-\\4-curpage-\\5',
            'article-show-article_id-\\1',
            'article-article-ac_id-\\1',
            'document-index-code-\\1',
            'search-index-cate_id-\\1-b_id-\\2-a_id-\\3-key-\\4-order-\\5-type-\\6-area_id-\\7-curpage-\\8',
            'brand-list-brand-\\1-key-\\2-order-\\3-type-\\4-area_id-\\5-curpage-\\6',
            'brand-index',
            'show_groupbuy-index-area_id-\\1-groupbuy_class-\\2-groupbuy_price-\\3-groupbuy_order_key-\\4-groupbuy_order-\\5-curpage-\\6',
            'show_groupbuy-groupbuy_soon-area_id-\\1-groupbuy_class-\\2-groupbuy_price-\\3-groupbuy_order_key-\\4-groupbuy_order-\\5-curpage-\\6',
            'show_groupbuy-groupbuy_history-area_id-\\1-groupbuy_class-\\2-groupbuy_price-\\3-groupbuy_order_key-\\4-groupbuy_order-\\5-curpage-\\6',
            'show_groupbuy-groupbuy_detail-group_id-\\1',
            'pointprod-index',
            'pointprod-plist',
            'pointprod-pinfo-id-\\1',
            'pointvoucher-index',
            'goods-comments_list-goods_id-\\1-type-\\2-curpage-\\3'
        );
        return preg_replace($reg_match_from,$reg_match_to,$path_info);
    }

    /**
     * CMS短网址还原成长网址
     * @param unknown $path_info
     * @return mixed
     */
    private function _cmsPathInfo($path_info) {
        $reg_match_from = array(
            '/^article-(\d+)$/',
            '/^picture-(\d+)$/'
        );
        $reg_match_to = array(
            'article-article_detail-article_id-\\1',
            'picture-picture_detail-picture_id-\\1'
        );
        return preg_replace($reg_match_from,$reg_match_to,$path_info);
    }

}
