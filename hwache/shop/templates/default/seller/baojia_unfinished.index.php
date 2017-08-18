<?php defined('InHG') or exit('Access Invalid!');?>

<div class="tabmenu">
  <ul class="tab pngFix">
    <li class="active"><a href="javascript:;">未完成报价</a></li>
  </ul>
</div>
<table class="ncsc-table-style">
  <thead>
    <tr nc_type="table_header">
      <th class="w30">&nbsp;</th>
      <th class="w50">&nbsp;</th>
      <th>基本信息</th>
      <th class="w100">数量</th>
      <th class="w100">操作</th>
    </tr>
    <?php if (!empty($output['bj_list'])) { ?>
    <tr>
      <td class="tc"><input type="checkbox" id="all" class="checkall"/></td>
      <td colspan="20"><label for="all" ><?php echo $lang['nc_select_all'];?></label>
        <a href="javascript:void(0);" class="ncsc-btn-mini" nc_type="batchbutton" uri="<?php echo urlShop('store_goods_online', 'drop_goods');?>" name="commonid" confirm="<?php echo $lang['nc_ensure_del'];?>"><i class="icon-trash"></i>删除</a>
      </td>
    </tr>
    <?php } ?>
  </thead>
  <tbody>
    <?php if (!empty($output['bj_list'])) { ?>
    <?php foreach ($output['bj_list'] as $val) { ?>
    <tr>
      <th class="tc"><input type="checkbox" class="checkitem tc" value="<?php echo $val['bj_serial']; ?>"/></th>
      <th colspan="20">平台编号：<?php echo $val['bj_serial'];?></th>
    </tr>
    <tr>
      <td class="w30">&nbsp;</td>
      <td><div class="pic-thumb"><a href="javascript:;" target="_blank"><img src="<?php echo thumb($val, 60);?>"/></a></div></td>
      <td class="tl"><dl class="goods-name">
          <dt><?php echo $val['gc_name']; ?></dt>
        </dl></td>
      <td><?php echo $val['bj_num'];?></td>
      <td class="nscs-table-handle">
        <span><a href="<?php echo urlShop('baojia_unfinished', 'geturl', array('serial' => $val['bj_serial']));?>" class="btn-blue"><i class="icon-edit"></i><p>编辑</p></a></span>
        <span><a href="javascript:void(0);" onclick="ajax_get_confirm('您确定要删除吗?', '<?php echo urlShop('baojia_unfinished', 'del', array('serial' => $val['bj_serial']));?>');" class="btn-red"><i class="icon-trash"></i><p>删除</p></a></span>
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
    <?php  if (!empty($output['bj_list'])) { ?>
    <tr>
      <th class="tc"><input type="checkbox" id="all2" class="checkall"/></th>
      <th colspan="10"><label for="all2"><?php echo $lang['nc_select_all'];?></label>
        <a href="javascript:void(0);" nc_type="batchbutton" uri="<?php echo urlShop('store_goods_online', 'drop_goods');?>" name="commonid" confirm="<?php echo $lang['nc_ensure_del'];?>" class="ncsc-btn-mini"><i class="icon-trash"></i>删除</a>
      </th>
    </tr>
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