<?php defined('InHG') or exit('Access Invalid!');?>

<div class="tabmenu">
  <ul class="tab pngFix">
    <li class="active"><a href="javascript:;">保险设置</a></li>
  </ul>
  <a href="<?php echo urlShop('store_baoxian','add');?>" class="ncsc-btn ncsc-btn-green" title="添加保险设置">添加保险设置</a> </div>
<table class="ncsc-table-style">
  <thead>
    <tr nc_type="table_header">
      <th class="w80">ID</th>
      <th>标题</th>
      <th class="w100">保险公司</th>
      <th class="w100">是否启用</th>
      <th class="w100">是否设置了费率</th>
      <th class="w100">是否为默认保险</th>
      <th class="w100">操作</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($output['list'])) { ?>
    <?php foreach ($output['list'] as $k => $v) { ?>
    <tr>
      <td class="trigger"><?php echo $v['id'] ?></td>
      <td><?php echo $v['title'];?></td>
      <td><?php echo $output['bxCom'][$v['co_id']]; ?></td>
      <td><?php if($v['is_enable']){?>是<?php }else{?>否<?php }?></td>
      <td><?php if($v['is_set']){?>是<?php }else{?>否<?php }?></td>
      <td><?php if($v['is_default']){?>是<?php }else{?>否<?php }?></td>
      <td class="nscs-table-handle">
        <span><a href="<?php echo urlShop('store_baoxian', 'edit', array('id' => $v['id']));?>" class="btn-blue"><i class="icon-edit"></i><p>编辑</p></a></span>
        <span><a href="<?php echo urlShop('store_baoxian', 'del', array('id' => $v['id']));?>" class="btn-red"><i class="icon-trash"></i><p>删除</p></a></span>
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
    <?php  if (!empty($output['list'])) { ?>
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