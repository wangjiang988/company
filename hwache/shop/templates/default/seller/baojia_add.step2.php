<?php defined('InHG') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<style>
  .ncsc-form-goods dl dt { width: 15%; }
  .ncsc-form-goods dl dd { width: 82%; }
</style>

<ul class="add-goods-step">
  <li><i class="icon icon-list-alt"></i>
    <h6>STIP.1</h6>
    <h2>选择车型</h2>
    <i class="arrow icon-angle-right"></i> </li>
  <li class="current"><i class="icon icon-edit"></i>
    <h6>STIP.2</h6>
    <h2>填写详情</h2>
    <i class="arrow icon-angle-right"></i> </li>
  <li><i class="icon icon-user-md"></i>
    <h6>STIP.3</h6>
    <h2>经销商信息</h2>
    <i class="arrow icon-angle-right"></i> </li>
  <li><i class="icon icon-wrench"></i>
    <h6>STIP.4</h6>
    <h2>选装件</h2>
    <i class="arrow icon-angle-right"></i> </li>
  <li><i class="icon icon-ok-circle"></i>
    <h6>STIP.5</h6>
    <h2>完成</h2>
  </li>
</ul>

<div class="item-publish">
  <form method="post" id="baojia_form" action="<?php echo urlShop('baojia_add', 'save_baojia');?>">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncsc-form-goods">
      <h3>车型基本信息</h3>
      <dl>
        <dt>车型分类<?php echo $lang['nc_colon'];?></dt>
        <dd id="gcategory">
          <?php echo $output['goods_class']['gc_tag_name'];?> <a class="ncsc-btn" href="<?php echo urlShop('baojia_add', 'add_step_one'); ?>"><?php echo $lang['nc_edit'];?></a>
          <input type="hidden" id="cate_id" name="cate_id" value="<?php echo $output['goods_class']['gc_id'];?>" class="text" />
          <input type="hidden" name="cate_name" value="<?php echo $output['goods_class']['gc_tag_name'];?>" class="text"/>
        </dd>
      </dl>
    <?php
    /**
     * 自定义字段信息数据开始
     */
    if (!empty($output['carmodel_fields'])) :
      foreach ($output['carmodel_fields'] as $k => $v) :
    ?>
      <dl>
        <dt> <?php if (!$v['readonly']) {echo '<i class="required">*</i>';}echo $v['title'].$lang['nc_colon'];?></dt>
        <dd>
          <?php if ($v['readonly']) {echo $v['value'];}else{?>
          <?php
            switch ($v['type']):
              case 'text':
              case 'datetime':
              ?>
              <input type="text" class="text w80 <?php echo $v['name'];?>" name="carmodel[<?php echo $v['name'];?>]" value="<?php echo $v['value'];?>" onclick="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM'})">
              <?php
              break;
              case 'float':
              case 'number':
              // 文本框
          ?>
            <input type="text" class="text w80 <?php echo $v['name'];?>" name="carmodel[<?php echo $v['name'];?>]" value="<?php echo $v['value'];?>">
          <?php
                  break;
              case 'textarea':
              // 多行文本框
          ?>
            <textares class="<?php echo $v['name'];?>" name="carmodel[<?php echo $v['name'] ?>]"><?php echo $v['value'];?></textares>
          <?php
                  break;
              case 'radio':
              case 'checkbox':
              // 单选框
          ?>
            <ul class="ncsc-form-radio-list">
            <?php
              if(!empty($v['setting'])):
                $tmp_arr = unserialize($v['setting']);
                $i = $v['value'];
              else:
                $tmp_arr = $v['value'];
                $i = 0;
              endif;
            ?>
            <?php
              foreach ($tmp_arr as $_k => $_v):
            ?>
              <li><label><input type="<?php echo $v['type'];?>" name="carmodel[<?php echo $v['name'] ?>]"<?php if($_k==$i){echo ' checked="checked"';}?> class="<?php echo $v['name'];?>" value="<?php echo $_k;?>"><?php echo $_v;?></label></li>
            <?php endforeach;?>
            </ul>
          <?php
                  break;
              case 'select':
              // 单选框
          ?>
            <select name="carmodel[<?php echo $v['name'] ?>]" class="<?php echo $v['name'];?>">
            <?php
              if(!empty($v['setting'])):
                $tmp_arr = unserialize($v['setting']);
                $i = $v['value'];
              else:
                $tmp_arr = $v['value'];
                $i = 0;
              endif;
            ?>
            <?php foreach ($tmp_arr as $_k => $_v): ?>
              <option value="<?php echo $_k;?>"<?php if($_k==$i){echo ' selected="selected"';}?>><?php echo $_v;?></option>
            <?php endforeach;?>
            </select>
          <?php
            endswitch; // End switch ($v['type'])
          ?>
          <?php } // End if ($v['readonly']) ?>
          <?php if (!empty($v['desc'])):?>
          <p class="hint"><?php echo $v['desc'];?></p>
          <?php endif;?>
        </dd>
      </dl>
      <?php if ($v['name']=='butie'):?>
      <dl >
        <dt>国家节能补贴<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <input name="butie_price" id="butie_price" value="0" type="text" class="text w80" <?php if(!$v['value']){echo 'disabled="disabled" style="background:#E7E7E7 none;"';}?>/><em class="add-on"><i class="icon-renminbi"></i></em> <span></span>
        </dd>
      </dl>
      <?php endif;?>
    <?php
      endforeach;
    endif;
    /**
     * 自定义字段数据结束
     */
    ?>
  <dl >
        <dt>协助地方政府置换补贴<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <ul class="ncsc-form-radio-list">
                          <li><label><input type="radio" value="0" class="butie" checked="checked" name="zf_butie">否</label></li>
              <li><label><input type="radio" value="1" class="butie" name="zf_butie">是</label></li>
            </ul>
        </dd>
      </dl>
      <dl >
        <dt>厂家或经销商补贴<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <ul class="ncsc-form-radio-list">
                          <li><label><input type="radio" value="0" class="butie" checked="checked" name="cj_butie">否</label></li>
              <li><label><input type="radio" value="1" class="butie" name="cj_butie">是</label></li>
            </ul>
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i>数量<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <ul class="ncsc-form-radio-list">
            <li>
              <select name="num" id="num">
                <?php
                // 交车周期
                for ($i=1; $i<11;$i++): ?>
                <option value="<?php echo $i;?>" <?php if($i==1){echo ' selected="selected"';} ?>><?php echo $i;?></option>
                <?php endfor;?>
              </select> 辆
            </li>
          </ul>
           <span></span>
        </dd>
      </dl>
      <dl>
      <dl>
        <dt><i class="required">*</i>出厂年月/交车周期<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <ul class="ncsc-form-radio-list">
            <li><label><input type="radio" name="hgradio" data-type="ccny" class="radio" checked="echcked" value="1"> 出厂年月</label><input type="text" class="w80 text" name="chuchang_time" id="chuchang_time" onClick="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM'})" value="<?php echo $output['goods_class']['chuchang_time'];?>" readonly="readonly" /></li>
            <li><label><input type="radio" name="hgradio" class="radio" value="0"> 交车周期</label>
              <select name="jc_period" id="jc_period" disabled="disabled" style="background:#E7E7E7 none;">
                <option value="0">请选择</option>
                <?php
                // 交车周期
                foreach ($output['jcPeriod'] as $k => $v): ?>
                <option value="<?php echo $k;?>"><?php echo $v;?></option>
                <?php endforeach;?>
              </select>
            </li>
          </ul>
           <span></span>
          <p class="hint">出厂年月和交车周期填一项即可</p>
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i>行驶里程<?php echo $lang['nc_colon'];?></dt>
        <dd>
          小于等于 <input name="licheng" value="" type="text" class="text w80" /> 公里<span></span>
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i>裸车开票价<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <input name="lckp_price" id="lckp_price" value="" type="text" class="text w80" /><em class="add-on"><i class="icon-renminbi"></i></em> <span></span>
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i>我的服务费<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <input name="agent_service_price" value="" type="text" class="text w80" /><em class="add-on"><i class="icon-renminbi"></i></em> <span></span>
          <p class="hint">我要收取的服务费用</p>
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i>消费者担保金<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <input name="doposit_price" value="" type="text" class="text w80" /><em class="add-on"><i class="icon-renminbi"></i></em> <span></span>
          <p class="hint">消费者违约补偿金，用于补偿经销商代理</p>
        </dd>
      </dl>
      <dl>
      <dl>
        <dt><i class="required">*</i>消费者上牌违约金<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <input name="plate_break_contract" value="0" type="text" class="text w80" /><em class="add-on"><i class="icon-renminbi"></i></em> <span></span>
          <p class="hint">消费者未在约定上牌地上牌，违约用于补偿经销商代理</p>
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i>付款方式<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <ul class="ncsc-form-radio-list">
            <?php
            // 付款方式
            foreach ($output['payType'] as $k => $v): ?>
            <li><label><input type="radio" name="pay_type" value="<?php echo $k;?>"<?php if($k==1){echo ' checked="checked"';}?>><?php echo $v;?></label></li>
            <?php endforeach;?>
          </ul>
        </dd>
      </dl>
      <dl>
        <dt>发布时间：</dt>
        <dd>
          <ul class="ncsc-form-radio-list">
            <li>
              <label>开始</label>
              <input type="text" class="w80 text" name="start_time" id="start_time" value="<?php echo date('Y-m-d');?>" readonly="readonly" onClick="WdatePicker({isShowClear:false,readOnly:true,minDate:'%y-%M-%d'})" />
            </li>
            <li>
              <label>结束</label>
              <input type="text" class="w80 text" name="end_time" id="end_time" value="<?php echo date('Y-m-d', time()+604800);?>" readonly="readonly" onClick="WdatePicker({isShowClear:false,readOnly:true,minDate:'#F{$dp.$D(\'start_time\')}'})" />
            </li>
          </ul>
        </dd>
      </dl>
    </div>
    <div class="bottom tc hr32">
      <label class="submit-border">
        <input type="submit" class="submit" value="下一步，选择经销商" />
      </label>
      &nbsp;&nbsp;&nbsp;&nbsp;
      <a href="<?php echo urlShop('baojia_add', 'add_step_one'); ?>">返回重新选择车型</a>
    </div>
  </form>
</div>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/datetime/WdatePicker.js"></script>
<script>
  $(function(){
    // 出场时间和交车周期填一项
    $('input[name="hgradio"]').click(function(){
        if($(this).attr('data-type') == 'ccny'){
            $('#chuchang_time').removeAttr('disabled').css('background','');
            $('#jc_period').attr('disabled','disabled').css('background','#E7E7E7 none');
        }else{
            $('#jc_period').removeAttr('disabled').css('background','');
            $('#chuchang_time').attr('disabled','disabled').css('background','#E7E7E7 none');
        }
    });
    // 国家补贴
    $('.butie').click(function(){
      if(parseInt($(this).val())==1){
        $('#butie_price').removeAttr('disabled').css('background','');
      } else {
        $('#butie_price').attr('disabled','disabled').css('background','#E7E7E7 none');
      }
    });

    // Submit
    $('#baojia_form').submit(function(){
      $(this).submit();
    });

  });
</script>
