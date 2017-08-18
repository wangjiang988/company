<?php
/**
 * 购物车操作
 */
defined('InHG') or exit('Access Invalid!');
class cartControl extends BaseBuyControl {
    const MANSONG_STATE_PUBLISHED = 2;

	public function __construct() {
		parent::__construct();
		Language::read('home_cart_index');

		$op = isset($_GET['op']) ? $_GET['op'] : $_POST['op'];

		//允许不登录就可以访问的op
		$op_arr = array('ajax_load','add','del');
		if (!in_array($op,$op_arr) && !$_SESSION['member_id'] ){
			$current_url = request_uri();
			redirect('index.php?act=login&ref_url='.urlencode($current_url));
		}
	}

	/**
	 * 购物车首页
	 */
	public function indexOp() {
        $model_cart	= Model('cart');

        //取出购物车信息
        $cart_list	= $model_cart->listCart('db',array('buyer_id'=>$_SESSION['member_id']));

        //取商品最新的在售信息
        $cart_list = $model_cart->getOnlineCartList($cart_list);

        //得到限时折扣信息
        $cart_list = $model_cart->getXianshiCartList($cart_list);

        //得到优惠套装状态,并取得组合套装商品列表
        $cart_list = $model_cart->getBundlingCartList($cart_list);

        //购物车商品以店铺ID分组显示,并计算商品小计,店铺小计与总价由JS计算得出
        $store_cart_list = array();
        foreach ($cart_list as $cart) {
            $cart['goods_total'] = ncPriceFormat($cart['goods_price'] * $cart['goods_num']);
            $store_cart_list[$cart['store_id']][] = $cart;
        }
        Tpl::output('store_cart_list',$store_cart_list);

        //店铺信息
        $store_list = Model('store')->getStoreMemberIDList(array_keys($store_cart_list));
        Tpl::output('store_list',$store_list);

        //取得店铺级活动 - 可用的满即送活动
	    $mansong_rule_list = $model_cart->getMansongRuleList(array_keys($store_cart_list));
	    Tpl::output('mansong_rule_list',$mansong_rule_list);

	    //取得哪些店铺有满免运费活动
        $free_freight_list = $model_cart->getFreeFreightActiveList(array_keys($store_cart_list));
        Tpl::output('free_freight_list',$free_freight_list);

        //标识 购买流程执行第几步
	    Tpl::output('buy_step','step1');
        Tpl::showpage(empty($cart_list) ? 'cart_empty' : 'cart');
	}

	/**
	 * 异步查询购物车
	 */
	public function ajax_loadOp() {
	    $model_cart	= Model('cart');
		if ($_SESSION['member_id']){
		    //登录后
			$cart_list	= $model_cart->listCart('db',array('buyer_id'=>$_SESSION['member_id']));
			$cart_array	= array();
			if(!empty($cart_list)){
			    $k = 0;
				foreach ($cart_list as $cart){
					$cart_array['list'][$k]['cart_id'] = $cart['cart_id'];
					$cart_array['list'][$k]['goods_id'] = $cart['goods_id'];
					$cart_array['list'][$k]['goods_name'] = $cart['goods_name'];
					$cart_array['list'][$k]['goods_price'] 	= $cart['goods_price'];
					$cart_array['list'][$k]['goods_image']	= thumb($cart,60);
					$cart_array['list'][$k]['goods_num'] = $cart['goods_num'];
					$cart_array['list'][$k]['goods_url'] = urlShop('goods', 'index', array('goods_id' => $cart['goods_id']));
					$k++;
				}
			}
		} else {
		    //登录前
		    $save_type = C('cache.type') == 'file' ? 'cookie' : 'cache';
			$cart_list = $model_cart->listCart($save_type);
			foreach ($cart_list as $key => $cart){
			    $value = array();
			    $value['cart_id'] = $key;
				$value['goods_id'] = $cart['goods_id'];
				$value['goods_name'] = $cart['goods_name'];
				$value['goods_price'] = $cart['goods_price'];
				$value['goods_num'] = $cart['goods_num'];
				$value['goods_image'] = thumb($cart,60);
				$value['goods_url'] = urlShop('goods', 'index', array('goods_id' => $cart['goods_id']));
				$cart_array['list'][] = $value;
			}
		}
		setNcCookie('cart_goods_num',$model_cart->cart_goods_num,2*3600);
		$cart_array['cart_all_price'] = ncPriceFormat($model_cart->cart_all_price);
		$cart_array['cart_goods_num'] = $model_cart->cart_goods_num;
		$cart_array = strtoupper(CHARSET) == 'GBK' ? Language::getUTF8($cart_array) : $cart_array;
        $json_data = json_encode($cart_array);
        if (isset($_GET['callback'])) {
            $json_data = $_GET['callback']=='?' ? '('.$json_data.')' : $_GET['callback']."($json_data);";
        }
        exit($json_data);
	}

	/**
	 * 加入购物车，登录后存入购物车表
	 * 登录前，如果开启缓存，存入缓存，否则存入COOKIE，由于COOKIE长度限制，最多保存5个商品
	 * 未登录不能将优惠套装商品加入购物车，登录前保存的信息以goods_id为下标
	 *
	 */
	public function addOp() {
	    $model_goods = Model('goods');
        if (is_numeric($_GET['goods_id'])) {

            //商品加入购物车(默认)
            $goods_id = intval($_GET['goods_id']);
            $quantity = intval($_GET['quantity']);
            if ($goods_id <= 0) return ;
            $goods_info	= $model_goods->getGoodsOnlineInfo(array('goods_id'=>$goods_id));

            //判断是不是在限时折扣中，如果是返回折扣信息
            $xianshi_info = Model('cart')->getXianshiInfo($goods_info,$quantity);
            if (!empty($xianshi_info)) {
                $goods_info = $xianshi_info;
            }

            $this->_check_goods($goods_info,$_GET['quantity']);

        } elseif (is_numeric($_GET['bl_id'])) {

            //优惠套装加入购物车(单套)
            if (!$_SESSION['member_id']) {
                exit(json_encode(array('msg'=>'请先登录','UTF-8')));
            }
            $bl_id = intval($_GET['bl_id']);
            if ($bl_id <= 0) return ;
            $model_bl = Model('p_bundling');
            $bl_info = $model_bl->getBundlingInfo(array('bl_id'=>$bl_id));
            if (empty($bl_info) || $bl_info['bl_state'] == '0') {
                exit(json_encode(array('msg'=>'该优惠套装已不存在，建议您单独购买','UTF-8')));
            }

            //检查每个商品是否符合条件,并重新计算套装总价
            $bl_goods_list = $model_bl->getBundlingGoodsList(array('bl_id'=>$bl_id));
            $goods_id_array = array();
            $bl_amount = 0;
            foreach ($bl_goods_list as $goods) {
            	$goods_id_array[] = $goods['goods_id'];
            	$bl_amount += $goods['bl_goods_price'];
            }
            $model_goods = Model('goods');
            $goods_list = $model_goods->getGoodsOnlineList(array('goods_id'=>array(in,$goods_id_array)));
            foreach ($goods_list as $goods) {
                $this->_check_goods($goods,1);
            }

            //优惠套装作为一条记录插入购物车，图片取套装内的第一个商品图
            $goods_info    = array();
            $goods_info['store_id']	= $bl_info['store_id'];
            $goods_info['goods_id']	= $goods_list[0]['goods_id'];
            $goods_info['goods_name'] = $bl_info['bl_name'];
            $goods_info['goods_price'] = $bl_amount;
            $goods_info['goods_num']   = 1;
            $goods_info['goods_image'] = $goods_list[0]['goods_image'];
            $goods_info['store_name'] = $bl_info['store_name'];
            $goods_info['bl_id'] = $bl_id;
            $quantity = 1;
        }

        //已登录状态，存入数据库,未登录时，优先存入缓存，否则存入COOKIE
        if($_SESSION['member_id']) {
            $save_type = 'db';
            $goods_info['buyer_id'] = $_SESSION['member_id'];
        } else {
            $save_type = C('cache.type') != 'file' ? 'cache' : 'cookie';
        }
        $model_cart	= Model('cart');
        $insert = $model_cart->addCart($goods_info,$save_type,$quantity);
        if ($insert) {
            //购物车商品种数记入cookie
            setNcCookie('cart_goods_num',$model_cart->cart_goods_num,2*3600);
            $data = array('state'=>'true', 'num' => $model_cart->cart_goods_num, 'amount' => ncPriceFormat($model_cart->cart_all_price));
        } else {
            $data = array('state'=>'false');
        }
	    exit(json_encode($data));
	}

	/**
	 * 检查商品是否符合加入购物车条件
	 * @param unknown $goods
	 * @param number $quantity
	 */
	private function _check_goods($goods_info, $quantity) {
		if(empty($quantity)) {
			exit(json_encode(array('msg'=>Language::get('wrong_argument','UTF-8'))));
		}
		if(empty($goods_info)) {
			exit(json_encode(array('msg'=>Language::get('cart_add_goods_not_exists','UTF-8'))));
		}
		if ($goods_info['store_id'] == $_SESSION['store_id']) {
			exit(json_encode(array('msg'=>Language::get('cart_add_cannot_buy','UTF-8'))));
		}
		if(intval($goods_info['goods_storage']) < 1) {
			exit(json_encode(array('msg'=>Language::get('cart_add_stock_shortage','UTF-8'))));
		}
		if(intval($goods_info['goods_storage']) < $quantity) {
			exit(json_encode(array('msg'=>Language::get('cart_add_too_much','UTF-8'))));
		}
	}

	/**
	 * 购物车更新商品数量
	 */
	public function updateOp() {
		$cart_id	= intval(abs($_GET['cart_id']));
		$quantity	= intval(abs($_GET['quantity']));

		if(empty($cart_id) || empty($quantity)) {
			exit(json_encode(array('msg'=>Language::get('cart_update_buy_fail','UTF-8'))));
		}

		$model_cart = Model('cart');
		$model_goods= Model('goods');

		//存放返回信息
		$return = array();

		$cart_info = $model_cart->getCartInfo(array('cart_id'=>$cart_id,'buyer_id'=>$_SESSION['member_id']));
		if ($cart_info['bl_id'] == '0') {

		    //普通商品
		    $goods_id = intval($cart_info['goods_id']);
		    $goods_info	= $model_goods->getGoodsOnlineInfo(array('goods_id'=>$goods_id));
		    if(empty($goods_info)) {
		        $return['state'] = 'invalid';
		        $return['msg'] = '商品已被下架';
		        $return['subtotal'] = 0;
		        $model_cart->delCart('db',array('cart_id'=>$cart_id,'buyer_id'=>$_SESSION['member_id']));
		        exit(json_encode($return));
		    }

		    //如果是是在限时折扣中,json返回价格，重新计算
		    $xianshi_info = Model('cart')->getXianshiInfo($goods_info,$quantity);
		    if (!empty($xianshi_info)) {
		        $cart_info['goods_price'] = $xianshi_info['goods_price'];
		    }

		    if(intval($goods_info['goods_storage']) < $quantity) {
		        $return['state'] = 'shortage';
		        $return['msg'] = '库存不足';
		        $return['goods_num'] = $goods_info['goods_storage'];
		        $return['goods_price'] = $cart_info['goods_price'];
		        $return['subtotal'] = $cart_info['goods_price'] * $quantity;
		        $model_cart->editCart(array('goods_num'=>$goods_info['goods_storage']),array('cart_id'=>$cart_id,'buyer_id'=>$_SESSION['member_id']));
		        exit(json_encode($return));
		    }

		} else {

		    //优惠套装商品
		    $model_bl = Model('p_bundling');
		    $bl_goods_list = $model_bl->getBundlingGoodsList(array('bl_id'=>$cart_info['bl_id']));
		    $goods_id_array = array();
		    foreach ($bl_goods_list as $goods) {
		        $goods_id_array[] = $goods['goods_id'];
		    }
		    $cart_list[$key]['bl_goods_list'] = $model_goods->getGoodsOnlineList(array('goods_id'=>array(in,$goods_id_array)));

		    //如果其中有商品下架，删除
		    if (count($cart_list[$key]['bl_goods_list']) != count($goods_id_array)) {
		        $return['state'] = 'invalid';
		        $return['msg'] = '该优惠套装已经无效，建议您购买单个商品';
		        $return['subtotal'] = 0;
		        $model_cart->delCart('db',array('cart_id'=>$cart_id,'buyer_id'=>$_SESSION['member_id']));
		        exit(json_encode($return));
		    }

		    //如果有商品库存不足，更新购买数量到目前最大库存
		    foreach ($cart_list[$key]['bl_goods_list'] as $goods_info) {
		        if ($quantity > $goods_info['goods_storage']) {
		            $return['state'] = 'shortage';
		            $return['msg'] = '该优惠套装部分商品库存不足，<br/>建议您降低购买数量或购买库存足够的单个商品';
		            $return['goods_num'] = $goods_info['goods_storage'];
		            $return['goods_price'] = $cart_info['goods_price'];
		            $return['subtotal'] = $cart_info['goods_price'] * $quantity;
		            $model_cart->editCart(array('goods_num'=>$goods_info['goods_storage']),array('cart_id'=>$cart_id,'buyer_id'=>$_SESSION['member_id']));
		            exit(json_encode($return));
		            break;
		        }
		    }
		}

		$data = array();
        $data['goods_num'] = $quantity;
        $data['goods_price'] = $cart_info['goods_price'];
        $update = $model_cart->editCart($data,array('cart_id'=>$cart_id,'buyer_id'=>$_SESSION['member_id']));
		if ($update) {
		    $return = array();
			$return['state'] = 'true';
			$return['subtotal'] = $cart_info['goods_price'] * $quantity;
			$return['goods_price'] = $cart_info['goods_price'];
			$return['goods_num'] = $quantity;
		} else {
			$return = array('msg'=>Language::get('cart_update_buy_fail','UTF-8'));
		}
		exit(json_encode($return));
	}

	/**
	 * 购物车删除单个商品，未登录前使用goods_id，此时cart_id可能为0，登录后使用cart_id
	 */
	public function delOp() {
		$cart_id = intval($_GET['cart_id']);
		$goods_id = intval($_GET['goods_id']);
		if($cart_id < 0 || $goods_id < 0) return ;
		$model_cart	= Model('cart');
		$data = array();
		if ($_SESSION['member_id']) {
		    //登录状态下删除数据库内容
			$delete	= $model_cart->delCart('db',array('cart_id'=>$cart_id,'buyer_id'=>$_SESSION['member_id']));
			if($delete) {
			    $data['state'] = 'true';
			    $data['quantity'] = $model_cart->cart_goods_num;
			    $data['amount'] = $model_cart->cart_all_price;
			} else {
				$data['msg'] = Language::get('cart_drop_del_fail','UTF-8');
			}
		} else {
			//未登录时删除cookie或缓存的购物车信息
			$save_type = C('cache.type') == 'file' ? 'cookie' : 'cache';
			$delete	= $model_cart->delCart($save_type,array('goods_id'=>$goods_id));
			if($delete) {
			    $data['state'] = 'true';
			    $data['quantity'] = $model_cart->cart_goods_num;
			    $data['amount'] = $model_cart->cart_all_price;
			}
		}
		setNcCookie('cart_goods_num',$model_cart->cart_goods_num,2*3600);
		$json_data = json_encode($data);
        if (isset($_GET['callback'])) {
            $json_data = $_GET['callback']=='?' ? '('.$json_data.')' : $_GET['callback']."($json_data);";
        }
        exit($json_data);
	}
}
