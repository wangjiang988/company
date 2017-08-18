<?php defined('InHG') or exit('Access Invalid!');?>
<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="wrap-all ncu-order-view">
  
  <h3>订单信息</h3>
  
  <dl>
    <dt><?php echo $lang['store_order_state'].$lang['nc_colon'];?></dt>
    <dd><strong><?php echo $lang['order'][$output['order_status']]; ?></strong>(<?php echo $lang['order'][$output['order_sub_status']]; ?>)</dd>
    <dt><?php echo $lang['store_order_order_sn'].$lang['nc_colon'];?></dt>
    <dd> <?php echo $output['order_info']['order_num']; ?> </dd>
    <dt><?php echo $lang['store_order_add_time'].$lang['nc_colon'];?></dt>
    <dd><?php echo date("Y-m-d H:i:s",$output['order_info']['created_at']); ?></dd>
  </dl>
  <dl>
    <dt>联系人：</dt>
    <dd><?php echo $output['order_info']['username']; ?></dd>
    <dt>联系电话：</dt>
    <dd><?php echo $output['order_info']['phone']; ?></dd>
  </dl>

  <h3>报价信息</h3>
  <dl>
    <dt>报价编号：</dt>
    <dd><?php echo $output['order_info']['bj_serial']; ?></dd>
    <dt>车型：</dt>
    <dd><?php echo $output['order_info']['gc_name']; ?></dd>
    <dt>开始时间：</dt>
    <dd><?php echo $output['order_info']['bj_start_time']; ?></dd>
    <div class="clear"></div>
  </dl>
  <dl>
    <dt>结束时间：</dt>
    <dd><?php echo $output['order_info']['bj_end_time']; ?></dd>
    <dt>付款方式：</dt>
    <dd><?php echo $output['order_info']['bj_pay_type']; ?></dd>
    <dt>是否全国：</dt>
    <dd><?php echo $output['order_info']['bj_nationwide']; ?></dd>
  </dl>
  <dl>
    <dt>国家补贴：</dt>
    <dd><?php echo $output['order_info']['bj_butie']; ?></dd>
    <dt>出厂日期：</dt>
    <dd><?php echo $output['order_info']['bj_chuchang_time']; ?></dd>
    <dt>官网参数：</dt>
    <dd><a href="<?php echo $output['order_info']['bj_official_url']; ?>">点击查看</a></dd>
  </dl>
  <dl>
    <dt>国别：</dt>
    <dd><?php echo $output['order_info']['guobie']; ?></dd>
    <dt>座位数：</dt>
    <dd><?php echo $output['order_info']['seat_num']; ?></dd>
    <dt>厂家指导价：</dt>
    <dd><?php echo $output['order_info']['zhidaojia']; ?></dd>
  </dl>

  <dl>
    <dt>排放标准：</dt>
    <dd><?php echo $output['order_info']['paifang']; ?></dd>
    <dt>车身颜色：</dt>
    <dd><?php echo $output['order_info']['body_color']; ?></dd>
    <dt>内饰颜色：</dt>
    <dd><?php echo $output['order_info']['interior_color']; ?></dd>
  </dl>
    

