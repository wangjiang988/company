<?php defined('InHG') or exit('Access Invalid!');?>

<div class="tabmenu">
  <ul class="tab pngFix">
<li class="active"><a href="">已完成的报价</a></li></ul>
  <a href="<?php echo urlShop('baojia_add');?>" class="ncsc-btn ncsc-btn-green" title="<?php echo $lang['store_goods_index_add_goods'];?>"> 发布报价</a> </div>
<form method="post" action="index.php?act=store_goods_online&op=index">
  <table class="search-form">
    <input type="hidden" name="form_submit" value="ok" />
    <tr>
      <td>&nbsp;</td>
      
      <td class="">
      <select name="status" id="">
        <?php if ($output['status']){
            switch ($output['status']){
              case '0':
                echo "<option value=".$output['status'].">终止</option>";
                break;
                case '1':
                echo "<option value=".$output['status'].">正常</option>";
                break;
                case '2':
                echo "<option value=".$output['status'].">暂停</option>";
                break;
            }

          } ?>
          
        <option value="">选择状态</option>
        <option value="1">正常</option>
        <option value="2">暂停</option>
        <option value="0">终止</option>
      </select>
      <select name="shenhe" id="">
      <?php if ($output['shenhe']): ?>
        <option value="$output['shenhe']">
          <?php if ($output['shenhe']==1): ?>
            <?php echo '已审核'; ?>
            <?php else: ?>
              <?php echo '未审核'; ?>
          <?php endif ?>
        </option>
      <?php endif ?>
        <option value="">是否审核</option>
        <option value="1">已审核</option>
        
        <option value="0">未审核</option>
      </select>
      <select name="dealer" id="">
        
        <?php if ($output['jingxiaoshang']): ?>
          <option value="<?php echo $output['jingxiaoshang']; ?>"><?php echo $output['jingxiaoshang']; ?></option>
          
        <?php endif ?>
        <option value="">选择经销商</option>
        <?php foreach ($output['dealer'] as $key => $value): ?>
          <option value="<?php echo $value['d_name']; ?>"><?php echo $value['d_name']; ?></option>
        <?php endforeach ?>
      </select>
      <input type="text" class="text w150" name="keyword" value="<?php echo $output['keyword']; ?>"/><label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['nc_search'];?>" /></label></td>
      <td class="tc w70"> </td>
    </tr>
  </table>
</form>
<table class="ncsc-table-style">
  <thead>
    <tr nc_type="table_header">
      
      <th class="w100">报价编号</th>
      <th class="w100">车型</th>
      <th class="w100">经销商</th>
      <th class="w100">结束日期</th>
      <th class="w30">审核</th>
      <th class="w30">状态</th>
      <th class="w100"><?php echo $lang['nc_handle'];?></th>
    </tr>
    
  </thead>
  <tbody>
    <?php if (!empty($output['goods_list'])) { ?>
    <?php foreach ($output['goods_list'] as $val) { ?>
    
    <tr>
      <td><?php echo $val['bj_serial'];?></td>
      <td><?php echo $val['gc_name'];?></td>
      <td><?php echo $val['dealer_name'];?></td>
      <td><?php echo date('Y-m-d',$val['bj_end_time']);?></td>
      <td>
        <?php 
          switch ($val['bj_is_pass']) {
            case '0':
               echo "未审核";
              break;
            
            case '1':
              echo "通过审核";
              break;
              
          }
        ?>
      </td>
      <td>
        <?php 
          switch ($val['bj_status']) {
            case '0':
               echo "终止";
              break;
            
            case '1':
              echo "正常";
              break;
            case '2':
              echo "暂停";
              break;  
          }
        ?>
      </td>
      <td >
        <a href="<?php echo urlShop('store_goods_online', 'show', array('id' => $val['bj_id']));?>" >打开</a>
        <?php if($val['bj_status']!=0){ ?>
        <a href="<?php echo urlShop('store_goods_online', 'pause', array('id' => $val['bj_id']));?>" >暂停</a>
        <a href="<?php echo urlShop('store_goods_online', 'recover', array('id' => $val['bj_id']));?>" >恢复</a>
        <a href="<?php echo urlShop('store_goods_online', 'stop', array('id' => $val['bj_id']));?>" >终止</a>
        <a href="<?php echo urlShop('store_goods_online', 'set_time', array('id' => $val['bj_id']));?>" >设置有效时段</a>
        <?php }?>
        
        <a href="<?php echo urlShop('store_goods_online', 'copy', array('id' => $val['bj_id']));?>" >复制新报价</a>
      </td>
    </tr>
    <tr style="display:none;"><td colspan="20"><div class="ncsc-goods-sku ps-container"></div></td></tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <?php  if (!empty($output['goods_list'])) { ?>
    
    <tr>
      <td colspan="20"><div class="pagination"> <?php echo $output['show_page']; ?> </div></td>
    </tr>
    <?php } ?>
  </tfoot>
</table>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js"></script>
<script src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/store_goods_list.js"></script>
<script>
$(function(){
    //Ajax提示
    $('.tip').poshytip({
        className: 'tip-yellowsimple',
        showTimeout: 1,
        alignTo: 'target',
        alignX: 'center',
        alignY: 'top',
        offsetY: 5,
        allowTipHover: false
    });
    $('a[nctype="batch"]').click(function(){
        if($('.checkitem:checked').length == 0){    //没有选择
            return false;
        }
        var _items = '';
        $('.checkitem:checked').each(function(){
            _items += $(this).val() + ',';
        });
        _items = _items.substr(0, (_items.length - 1));

        var data_str = '';
        eval('data_str = ' + $(this).attr('data-param'));

        if (data_str.sign == 'jingle') {
            ajax_form('ajax_jingle', '设置广告词', data_str.url + '&commonid=' + _items + '&inajax=1', '480');
        } else if (data_str.sign == 'plate') {
            ajax_form('ajax_plate', '设置关联版式', data_str.url + '&commonid=' + _items + '&inajax=1', '480');
        }
    });
});
</script>