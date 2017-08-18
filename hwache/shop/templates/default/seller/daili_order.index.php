<?php defined('InHG') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css" />
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui-dialog.css" rel="stylesheet" type="text/css">
<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<form method="get" action="index.php" target="_self">
  <table class="search-form">
    <input type="hidden" name="act" value="daili_order" />
    <input type="hidden" name="op" value="index" />
    <?php if ($_GET['state_type']) { ?>
    <input type="hidden" name="state_type" value="<?php echo $_GET['state_type']; ?>" />
    <?php } ?>
    <tr>
      <td>&nbsp;</td>
      <th><?php echo $lang['store_order_add_time'];?></th>
      <td class="w240"><input type="text" class="text w70" name="query_start_date" id="query_start_date" value="<?php echo $_GET['query_start_date']; ?>" /><label class="add-on"><i class="icon-calendar"></i></label>&nbsp;&#8211;&nbsp;<input id="query_end_date" class="text w70" type="text" name="query_end_date" value="<?php echo $_GET['query_end_date']; ?>" /><label class="add-on"><i class="icon-calendar"></i></label></td>
       
      <th><?php echo $lang['store_order_order_sn'];?></th>
      <td class="w160"><input type="text" class="text w150" name="order_sn" value="<?php echo $_GET['order_sn']; ?>" /></td><td class="w70 tc"><label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['store_order_search'];?>" /></label></td>
    </tr>
  </table>
</form>
<table class="ncsc-table-style order">
  <thead>
    <tr>
      <th class="w10"></th>
      <th colspan="2"><?php echo $lang['store_order_goods_detail'];?></th>
      <th class="w70"><?php echo $lang['store_order_goods_single_price'];?></th>
      <th class="w50"><?php echo $lang['store_show_order_amount'];?></th>
      <th class="w110"><?php echo $lang['store_order_buyer'];?></th>
      <th class="w110"><?php echo $lang['store_order_sum'];?></th>
      <th class="w110"><?php echo $lang['store_order_order_stateop'];?></th>
    </tr>
  </thead>
  <?php
  /**
   * 此处循环显示订单
   */
  if (is_array($output['orderList']) and !empty($output['orderList'])) { ?>
  <?php foreach($output['orderList'] as $k => $v) { ?>
  <tbody>
    <tr>
      <td colspan="20" class="sep-row"></td>
    </tr>
    <tr>
      <th colspan="20">
        <span class="fl ml10"><?php /*订单编号*/ echo $lang['order_sn'].$lang['nc_colon'];?><span class="goods-num"><em><?php echo $v['order_num'];?></em></span></span>
        <span class="fl ml10"><?php /*消费者*/ echo $lang['buyer_name'].$lang['nc_colon'];?><?php echo $v['buyer_name']?></span>
        <span class="fl ml20"><?php /*下单时间*/ echo $lang['order_add_time'].$lang['nc_colon'];?><em class="goods-time"><?php echo $v['created_at']; ?></em></span>
        <span class="fr mr5">
          当前状态：<?php echo $lang['order'][$v['cart_status']]; ?>(<?php echo $lang['order'][$v['cart_sub_status']]; ?>)
        </span>
      </th>
    </tr>
    <tr>
      <td colspan="20">
        <span class="fl ml10"><?php /*车型*/ echo $lang['car_brand'].$lang['nc_colon'].$v['car_name'];?></span>
        <span class="fl ml20"><?php /*厂商指导价*/ echo $lang['changshang_price'].$lang['nc_colon'].$v['zhidaojia'];?></span>
        <span class="fl ml20"><?php /*国别*/ echo $lang['guobie'].$lang['nc_colon'].$v['guobie'];?></span>
        <span class="fl ml20"><?php /*座位数*/ echo $lang['seat_num'].$lang['nc_colon'].$v['seat_num'];?></span>
      </td>
    </tr>
    <tr>
      <td colspan="20">
        <span class="fl ml10"><?php /*车身颜色*/ echo $lang['body_color'].$lang['nc_colon'].$v['body_color'];?></span>
        <span class="fl ml20"><?php /*内饰颜色*/ echo $lang['interior_color'].$lang['nc_colon'].$v['interior_color'];?></span>
        <span class="fl ml20"><?php /*排放*/ echo $lang['paifang'].$lang['nc_colon'].$v['paifang'];?></span>
        <span class="fl ml20"><?php /*出厂年限-交车周期*/ if($v['bj_jc_period']==0){echo $lang['producetime'].$lang['nc_colon'].$v['bj_producetime'];}else{echo $lang['jc_period'].$lang['nc_colon'].$v['bj_jc_period'];} ?></span>
        <span class="fl ml20"><?php /*行驶里程*/ echo $lang['licheng'].$lang['nc_colon'];?>小于等于<?php echo $v['bj_licheng'];?>公里</span>
      </td>
    </tr>
    <tr>
      <td colspan="20">
        <span class="fl ml10"><?php /*裸车开票价*/ echo $lang['lckp'].$lang['nc_colon'].$v['bj_lckp_price'];?></span>
        <span class="fl ml10"><?php /*经销商*/ echo $lang['dealer'].$lang['nc_colon'].$v['dealer_name'];?></span>
        <span class="fr mr5">

          <?php if ($v['cart_sub_status'] == 'order_earnest_not_confirm') : //确认订单?>
          <a href="javascript:;" data-id="<?php echo $v['order_num'];?>" class="ncsc-btn-mini order-confirm"><i class="icon-file-text-alt"></i>确认订单</a>
          <?php endif;?>

          <?php if ($v['cart_sub_status'] == 'order_doposit_not_confirm') : //确认担保金?>
          <a href="javascript:;" data-id="<?php echo $v['order_num'];?>" class="ncsc-btn-mini order-doposit-confirm"><i class="icon-file-text-alt"></i>确认担保金</a>
          <?php endif;?>

          <?php if ($v['cart_sub_status'] == 'order_jiaoche_wait') : //发送交车通知?>
          <a href="<?php echo WWW_SITE_URL;?>/cart/yuyue/<?php echo $v['order_num'];?>/sell" class="ncsc-btn-mini"><i class="icon-file-text-alt"></i>发送交车通知</a>
          <?php endif;?>

          <?php if ($v['cart_sub_status'] == 'order_jiaoche_confirm') : //确认交车时间?>
          <a href="javascript:;" data-id="<?php echo $v['order_num'];?>" class="ncsc-btn-mini order_jiaoche_confirm"><i class="icon-file-text-alt"></i>确认交车时间</a>
          <?php endif;?>

          <!-- <a href="index.php?act=daili_order&op=show_order&order_id=<?php echo $v['order_num'];?>" target="_blank" class="ncsc-btn-mini"><i class="icon-file-text-alt"></i>查看订单</a> -->
          <a href="<?php echo WWW_SITE_URL.'/getmyorderdaili/'.$v['order_num'];?>" target="_blank" class="ncsc-btn-mini"><i class="icon-file-text-alt"></i>查看订单</a>
          <a href="index.php?act=daili_order&op=print&order_id=<?php echo $v['order_num'];?>" class="ncsc-btn-mini" target="_blank" title="打印"><i class="icon-print"></i>打印</a>
        </span>
      </td>
    </tr>
    <?php } } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <?php if (is_array($output['orderList']) and !empty($output['orderList'])) { ?>
    <tr>
      <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
    </tr>
    <?php } ?>
  </tfoot>
</table>
<script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" /></script>
<script src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/dialog-min.js"></script>
<script type="text/javascript">
$(function(){
  $('#query_start_date').datepicker({dateFormat: 'yy-mm-dd'});
  $('#query_end_date').datepicker({dateFormat: 'yy-mm-dd'});
  $('.checkall_s').click(function(){
      var if_check = $(this).attr('checked');
      $('.checkitem').each(function(){
          if(!this.disabled)
          {
              $(this).attr('checked', if_check);
          }
      });
      $('.checkall_s').attr('checked', if_check);
  });
  // 确认订单,诚意金
  $(".order-confirm").click(function(){
    if (confirm("请确认订单")) {
      var data_id = $(this).attr("data-id");
      $.getJSON("index.php?act=daili_order&op=confirm",{order_id:data_id},
        function(json){
          alert(json.msg);
          if (json.code == 1) {
            window.location.reload();
          }
      });
    }
  });
  // 确认担保金
  $('.order-doposit-confirm').click(function() {
    if (confirm("请确认担保金")) {
      var data_id = $(this).attr("data-id");
      $.getJSON("index.php?act=daili_order&op=doposit_confirm",{order_id:data_id},
        function(json){
          alert(json.msg);
          if (json.code == 1) {
            window.location.reload();
          }
      });
    }
  });
  // 确认交车时间
  $('.order_jiaoche_confirm').click(function() {
    if (confirm("确认交车时间")) {
      var data_id = $(this).attr("data-id");
      $.getJSON("index.php?act=daili_order&op=jiaoche_confirm",{order_id:data_id},
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
