<?php defined('InHG') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<style type="text/css">
.store-name {
	width: 130px;
	display: inline-block;
	overflow: hidden;
	white-space: nowrap;
	text-overflow: ellipsis;
}
.padding-top5{padding:5px 0;}
</style>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <form method="get" action="index.php" target="_self">
    <table class="search-form">
      <input type="hidden" name="act" value="member_order" />
      <tr>
        <td></td>
        <th><?php echo $lang['member_order_time'].$lang['nc_colon'];?></th>
        <td class="w180"><input type="text" class="text" name="query_start_date" id="query_start_date" value="<?php echo $_GET['query_start_date']; ?>"/>
          &#8211;
          <input type="text" class="text" name="query_end_date" id="query_end_date" value="<?php echo $_GET['query_end_date']; ?>"/></td>
        <th><?php echo $lang['member_order_sn'].$lang['nc_colon'];?></th>
        <td class="w160"><input type="text" class="text" name="order_sn" value="<?php echo $_GET['order_sn']; ?>"></td>
        <td class="w90 tc"><input type="submit" class="submit" value="<?php echo $lang['member_order_search'];?>" /></td>
      </tr>
    </table>
  </form>
  <table class="order ncu-table-style">
    <?php if ($output['orderList']) { ?><?php foreach ($output['orderList'] as $k => $v) : ?>
      <tr><td colspan="19" class="sep-row"></td></tr>
      <tr>
        <th colspan="19">
          <span class="fl ml10">
            订单号：<span class="goods-num"><em><?php echo $v['order_num'];?></em></span></span>
            <span class="fl ml20">下单时间：<em class="goods-time"><?php echo $v['created_at']; ?></em></span>
            <span class="fl ml10"></span>
            <span class="fl ml10" style="color:#f30">订单状态：<?php echo $lang['order'][$v['cart_status']];?>(<?php echo $lang['order'][$v['cart_sub_status']];?>)</span>
        </th>
      </tr>

      <tr>
        <td class="w10 bdl"></td>
        <td class="w70">
          <div class="goods-pic-small">
            <span class="thumb size60"><i></i><a href="<?php echo WWW_SITE_URL;?>/show/<?php echo $v['bj_serial'];?>" target="_blank"><img style="width:60px;" src="<?php echo UPLOAD_SITE_URL;?>/shop/common/category-pic-<?php echo $v['brand_id']?>.jpg"/></a></span>
          </div>
        </td>
        <td>
          <dl class="goods-name">
            <dt><a href="<?php echo WWW_SITE_URL;?>/show/<?php echo $v['bj_serial'];?>" target="_blank"><?php echo $v['car_name'];?></a></dt>
            <dd>厂商指导价:<?php echo $v['zhidaojia'];?> 国别:<?php echo $v['guobie'];?> 排放标准:<?php echo $v['paifang'];?></dd>
            <dd>外饰:<?php echo $v['body_color'];?> 内饰:<?php echo $v['interior_color'];?> 座位数:<?php echo $v['seat_num'];?></dd>
          </dl>
        </td>
        <td class="goods-price w120">
          <i><?php echo $v['bj_price'];?></i>
        </td>
        <td class="w120 bdl" rowspan="1">
          <p class="goods-pay">诚意金：￥<?php echo $v['bj_earnest_price'];?></p>
          <p class="goods-pay">担保金：￥<?php echo $v['bj_car_guarantee'] - $v['bj_earnest_price'];?></p>
        </td>
        <td class="bdl bdr w120" rowspan="1">
          <?php if($v['cart_status']=='order_place')://付诚意金?>
          <p class="padding-top5"><a href="<?php echo WWW_SITE_URL;?>/pay/earnest/<?php echo $v['order_num'];?>" class="nc-show-order">支付诚意金</a></p>
          <p class="padding-top5"><a href="#" class="nc-show-order">取消订单</a></p>
          <?php endif;?>

          <?php if($v['cart_status']=='order_doposit' && $v['cart_sub_status']=='order_doposit_wait_pay')://付担保金?>
          <p class="padding-top5"><a href="<?php echo WWW_SITE_URL;?>/pay/deposit/<?php echo $v['order_num'];?>" class="nc-show-order">支付担保金</a></p>
          <?php endif;?>

          <?php if($v['cart_status']=='order_jiaoche' && $v['cart_sub_status']=='order_jiaoche_sent_notify')://交车通知?>
          <p class="padding-top5"><a href="<?php echo WWW_SITE_URL;?>/cart/yuyue/<?php echo $v['order_num'];?>" class="nc-show-order">确认交车时间</a></p>
          <?php endif;?>

          <?php if($v['cart_status']=='order_tiche' && $v['cart_sub_status']=='order_tiche_info_check')://确认提车信息?>
          <p class="padding-top5"><a href="<?php echo WWW_SITE_URL;?>/cart/tiche/<?php echo $v['order_num'];?>" class="nc-show-order">确认提车信息</a></p>
          <?php endif;?>

          <?php if($v['cart_status']=='order_tiche' && $v['cart_sub_status']=='order_tiche_offline')://完成提车?>
          <p class="padding-top5"><a href="javascript:;" data-id="<?php echo $v['order_num'];?>" class="nc-show-order order_tiche_offline">完成提车</a></p>
          <?php endif;?>

          <p class="padding-top5"><a href="<?php echo WWW_SITE_URL.'/getmyorder/'.$v['order_num'];?>" target="_blank" class="nc-show-order"><i></i>查看订单</a></p>

        </td>
      </tr>

      <?php endforeach; ?>

      <?php } else { ?>
      <tbody>
      <tr>
        <td colspan="19" class="norecord"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></td>
      </tr>
      </tbody>
      <?php } ?>

    <?php if($output['orderList']) { ?>
    <tfoot>
      <tr>
        <td colspan="19"><div class="pagination"> <?php echo $output['show_page']; ?> </div></td>
      </tr>
    </tfoot>
    <?php } ?>
  </table>
</div>
<script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" ></script>
<script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/sns.js" ></script>
<script type="text/javascript">
$(function(){
  $('#query_start_date').datepicker({dateFormat: 'yy-mm-dd'});
  $('#query_end_date').datepicker({dateFormat: 'yy-mm-dd'});
  // 确认完成提车
  $('.order_tiche_offline').click(function() {
    if (confirm("确认完成提车")) {
      var data_id = $(this).attr("data-id");
      $.getJSON("index.php?act=m_order&op=tiche_confirm",{order_id:data_id},
        function(json){
          alert(json.msg);
          if (json.code == 1) {
            window.location.reload();
          }
      });
    }
  });
});
</script>
