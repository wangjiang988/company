<?php defined('InHG') or exit('Access Invalid!');?>

<div class="tabmenu">
  <ul class="tab pngFix">
    <li class="active"><a href="javascript:;">设置经销商参数</a></li>
  </ul>
</div>
<div class="item-publish">
  <form method="post" id="goods_form" action="<?php echo urlShop('store_setting', 'select_dealer'); ?>">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="d_id" value="<?php echo $output['d_id'];?>" />
    <div class="ncsc-form-goods">
      <h3>设置经销商参数</h3>
      <dl>
        <dt>经销商名称：</dt>
        <dd><?php echo $output['dealer_info']['d_name'];?></dd>
      </dl>
      <dl>
        <dt>营业地址：</dt>
        <dd><?php echo $output['dealer_info']['d_yy_place'];?></dd>
      </dl>
      <dl>
        <dt>归属地：</dt>
        <dd><?php echo $output['dealer_info']['d_areainfo'];?></dd>
      </dl>
      <dl>
        <dt><i class="required">*</i>交车地点：</dt>
        <dd nc_type="no_spec">
          <input name="jc_place" value="<?php echo $output['dealer_info']['d_jc_place']; ?>" type="text"  class="text w380" />
          <p class="hint">你可以设置交车地点，默认和经销商营业地点相同。</p>
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i>交车地图位置：</dt>
        <dd>
          <input name="jc_lngx" id="jc_lngx" value="<?php echo $output['dealer_info']['d_jc_lngx']; ?>" type="hidden" /><input name="jc_lngy" id="jc_lngy" value="<?php echo $output['dealer_info']['d_jc_lngy']; ?>" type="hidden" /><input id="loadmap" type="button" value="加载地图信息" />
          <p class="hint">加载地图信息可以在地图上选择交车地点，更加直观展示。</p>
        </dd>
      </dl>
      <dl>
        <dt>保险：</dt>
        <dd>
          <input name="baoxian" <?php if($output['dealer_info']['d_baoxian']){echo ' checked="checked"';} ?> type="checkbox" />&nbsp;&nbsp;商业保险是否必须在销售商处购买
        </dd>
      </dl>
      <dl>
        <dt>上牌：</dt>
        <dd><input name="shangpai" <?php if($output['dealer_info']['d_shangpai']){echo ' checked="checked"';} ?> type="checkbox" />&nbsp;&nbsp;本地客户是否必须由销售商提供上牌服务
        </dd>
      </dl>
      <dl>
        <dt>临牌：</dt>
        <dd><input name="linpai" <?php if($output['dealer_info']['d_linpai']){echo ' checked="checked"';} ?> type="checkbox" />&nbsp;&nbsp;临时车辆移动牌照是否必须由销售商提供办理服务
        </dd>
      </dl>
      <dl>
        <dt>交车收费服务：</dt>
        <dd><input name="jc_fee" <?php if($output['dealer_info']['d_jc_fee']){echo ' checked="checked"';} ?> type="checkbox" />&nbsp;&nbsp;是否交车时有其他收费项目
        </dd>
      </dl>
      <dl>
        <dt>交车免费服务：</dt>
        <dd><input name="jc_free" <?php if($output['dealer_info']['d_jc_free']){echo ' checked="checked"';} ?> type="checkbox" />&nbsp;&nbsp;是否提供赠品或免费附加服务
        </dd>
      </dl>
      <dl>
        <dt>限购城市上牌：</dt>
        <dd><input name="xg_shangpai" <?php if($output['dealer_info']['d_xg_shangpai']){echo ' checked="checked"';} ?> type="checkbox" />&nbsp;&nbsp;限购城市是否可办理上牌指标
        </dd>
      </dl>
      <dl>
        <dt>国家补贴：</dt>
        <dd><input name="butie" <?php if($output['dealer_info']['d_butie']){echo ' checked="checked"';} ?> type="checkbox" />&nbsp;&nbsp;国家补贴车型是否可销售商可办国家节能补贴
        </dd>
      </dl>
      <dl>
        <dt>交车联系人：</dt>
        <dd>
          <p>姓名：<input name="data[0][lxr]" value="" type="text" class="text w100" />&nbsp;&nbsp;电话：<input name="data[0][phone]" value="" type="text" class="text w100" /></p>
          <p><a href="javascript:;" id="add_lxr" class="ncsc-btn ncsc-btn-acidblue mt10"><i class="icon-plus"></i>添加联系人</a></p>
          <p class="hint"><?php echo $lang['store_goods_index_goods_stock_help'];?></p>
        </dd>

    </div>
    <div class="bottom tc hr32">
      <label class="submit-border">
        <input type="submit" class="submit" value="提交" />
      </label>
    </div>
  </form>
</div>
<script type="text/javascript">

var SITEURL = "<?php echo SHOP_SITE_URL; ?>";
var DEFAULT_GOODS_IMAGE = "<?php echo thumb(array(), 60);?>";
var SHOP_RESOURCE_SITE_URL = "<?php echo SHOP_RESOURCE_SITE_URL;?>";

$(function(){
  var n = 1;
  $('#add_lxr').click(function(){
    var html = '<div class="ncs-message-list">姓名：<input name="data['+n+'][lxr]" value="" type="text" class="text w100" />&nbsp;&nbsp;电话：<input name="data['+n+'][phone]" value="" type="text" class="text w100" />&nbsp;&nbsp;<a href="javascript:void(0);" class="ncsc-btn hgdel"><i class="icon-trash"></i>删除</a></div>';
    $(this).parent().before(html);
    n++;
  });

  $('.hgdel').live('click',function(){
    $(this).parent().remove();
  });

});
</script>