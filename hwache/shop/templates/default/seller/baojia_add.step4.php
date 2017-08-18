
<ul class="add-goods-step">
  <li><i class="icon icon-list-alt"></i>
    <h6>STIP.1</h6>
    <h2>选择车型</h2>
    <i class="arrow icon-angle-right"></i> </li>
  <li><i class="icon icon-edit"></i>
    <h6>STIP.2</h6>
    <h2>填写详情</h2>
    <i class="arrow icon-angle-right"></i> </li>
  <li><i class="icon icon-user-md"></i>
    <h6>STIP.3</h6>
    <h2>经销商信息</h2>
    <i class="arrow icon-angle-right"></i> </li>
  <li class="current"><i class="icon icon-wrench"></i>
    <h6>STIP.4</h6>
    <h2>选装件</h2>
    <i class="arrow icon-angle-right"></i> </li>
  <li><i class="icon icon-ok-circle"></i>
    <h6>STIP.5</h6>
    <h2>完成</h2>
  </li>
</ul>

<div class="item-publish">
  <form method="post" id="form" action="<?php echo urlShop('baojia_add', 'add_step_four');?>">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="bjid" value="<?php echo $output['bjid'];?>" />
    <div class="ncsc-form-goods">
      <h3>选装件</h3>
      <dl>
        <dt>车型<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <?php echo $output['car_brand'];?>
        </dd>
      </dl>
      <dl>
        <dt>经销商<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <?php echo $output['dealer_name'];?>
        </dd>
      </dl>
      <dl>
        <dt>选装件折扣率<?php echo $lang['nc_colon'];?></dt>
        <dd><input type="text" name="bj_xzj_zhekou" id="bj_xzj_zhekou" value="100"> %</dd>
      </dl>
      <?php if(!empty($output['xzj_list'])): ?>
      <dl>
        <dt>原厂选装件<?php echo $lang['nc_colon'];?></dt>
        <dd>

          <table border="0" cellpadding="0" cellspacing="0" class="spec_table">
            <thead>
              <tr>
                <th>已安装</th>
                <th>名称</th>
                
                <th>型号</th>
                <th>厂商指导价</th>
                <th>数量</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($output['xzj_list']['ycxzj'] as $k => $v): ?>
              <tr>
                <td><input type="checkbox" name="xzj[<?php echo $v['xzj_list_id'];?>]">

                <input type="hidden" name="m_id[<?php echo $v['xzj_list_id'];?>]" value="<?php echo $v['id'];?>"></td>
                <td><?php echo $v['xzj_title'];?></td>
                
                <td><?php echo $v['xzj_model'];?></td>
                
                <td><input type="text" name="guide_price[<?php echo $v['xzj_list_id'];?>]" value="<?php echo $v['xzj_guide_price'];?>" readonly class="slpzj text w60"></td>
                <td><input type="text" value="<?php echo $v['xzj_max_num'];?>" name="num[<?php echo $v['xzj_list_id'];?>]" class="slpzj text w60">(最多<?php echo $v['xzj_max_num'];?>)</td>
                
              </tr>
            <?php endforeach;?>
            </tbody>
          </table>
          
        </dd>
      </dl>
	  
      <?php endif;?>
      

      
      <!-- <?php if(!empty($output['xzj_list']['fycXzjs'])): ?>
      <dl>
        <dt>非原厂选装件设置<?php echo $lang['nc_colon'];?><br>(非原厂选装件将做为赠送礼品)</dt>
        <dd>
        <table border="0" cellpadding="0" cellspacing="0" class="spec_table">
            <thead>
              <tr>
                <th>可供安装</th>
                <th>名称</th>
                <th>品牌</th>
                <th>型号</th>
                <th>数量</th>
                <th>价格</th>
                <th>安装费</th>               
                
              </tr>
            </thead>
            <tbody>
            <?php foreach ($output['xzj_list']['fycXzjs'] as $k => $v): ?>
            <tr>
            <td><input type="checkbox" name="fycxzj[<?php echo $v['xzj_list_id'];?>]">
            <input type="hidden" name="m_id[<?php echo $v['xzj_list_id'];?>]" value="<?php echo $v['id'];?>">
            <input type="hidden" name="xzj_title[<?php echo $v['xzj_list_id'];?>]" value="<?php echo $v['xzj_title'];?>">
            </td>
                <td><?php echo $v['xzj_title'];?></td>
                <td><?php if($v['xzj_yc']){echo '-';}else{echo $v['xzj_brand'];}?></td>
                <td><?php echo $v['xzj_model'];?></td>
                <td><input type="text" name="num[<?php echo $v['xzj_list_id'];?>]" value="1" readonly class="slpzj text w60"></td>
                <td><input type="text" name="guide_price[<?php echo $v['xzj_list_id'];?>]" value="<?php echo $v['xzj_price'];?>"  class="slpzj text w60"></td>
                <td><input type="text" value="<?php echo $v['xzj_fee'];?>" name="fee[<?php echo $v['xzj_list_id'];?>]" class="slpzj text w60"></td>
                
            </tr>
             <?php endforeach;?>
            </tbody></table>
        </dd>
      </dl>
      <?php endif;?> -->
      
      
    </div>
    <div class="bottom tc hr32">
      <label class="submit-border">
        <input type="submit" class="submit" value="下一步" />
        <!--<input type="hidden" class="m_id" value="<?php echo $output['m_id'];?>" />-->
        <input type="hidden" class="dealer_id" value="<?php echo $output['dealer_id'];?>" />
      </label>
    </div>
  </form>
</div>
<script>
//修改折扣率时对应的联动修改
$('input[name="bj_xzj_zhekou"]').keyup(function(){
	var discount = parseFloat($('input[name="bj_xzj_zhekou"]').val(),2);
    $("input[name^='price[']").each(function(){
		var tmpId = $(this).attr("id");
		if(isNaN(tmpId)){//原厂选装件前置数据判断
			tmpId = tmpId.replace(/[^0-9]/ig,"");
			var num = parseInt($("input[name='num[yc_front]["+tmpId+"]']").val());
			var fee = parseFloat($("input[name='fee[yc_front]["+tmpId+"]']").val(),2);
			var guidePrice = parseFloat($("input[name='guide_price[yc_front]["+tmpId+"]']").val(),2);			
		}else{
			var num = parseInt($("input[name='num["+tmpId+"]']").val());
			var fee = parseFloat($("input[name='fee["+tmpId+"]']").val(),2);
			var guidePrice = parseFloat($("input[name='guide_price["+tmpId+"]']").val(),2);
		}
		var totalPrice = num*guidePrice*discount/100+fee;
		if(num >0){
			$(this).val(totalPrice.toFixed(2));
		}else{
			$(this).val("0.00");
		}
    });
    
});
//修改安装费时对应的联动修改
$('input[name^=fee]').keyup(function(){
	var discount = parseFloat($('input[name="bj_xzj_zhekou"]').val(),2);
	var midTmp = "";
	tmpId = $(this).attr("name").replace(/[^0-9]/ig,"");
	if($(this).attr("name").indexOf("fee[yc_front]")>-1){//选装件前置数据判断
		var midTmp = "[yc_front]";
	}
	var num = parseInt($("input[name='num"+midTmp+"["+tmpId+"]']").val());
	var fee = parseFloat($("input[name='fee"+midTmp+"["+tmpId+"]']").val(),2);
	var guidePrice = parseFloat($("input[name='guide_price"+midTmp+"["+tmpId+"]']").val(),2);
	var totalPrice = num*guidePrice*discount/100+fee;
	if(num >0){
		$("input[name='price"+midTmp+"["+tmpId+"]']").val(totalPrice.toFixed(2));
	}else{
		$("input[name='price"+midTmp+"["+tmpId+"]']").val("0.00");
	}	
});
//修改选装件数据量时对应的联动修改
$('input[name^=num]').keyup(function(){
	var discount = parseFloat($('input[name="bj_xzj_zhekou"]').val(),2);
	var midTmp = "";
	tmpId = $(this).attr("name").replace(/[^0-9]/ig,"");
	if($(this).attr("name").indexOf("num[yc_front]")>-1){//选装件前置数据判断
		var midTmp = "[yc_front]";
	}
	var num = parseInt($("input[name='num"+midTmp+"["+tmpId+"]']").val());
	var fee = parseFloat($("input[name='fee"+midTmp+"["+tmpId+"]']").val(),2);
	var guidePrice = parseFloat($("input[name='guide_price"+midTmp+"["+tmpId+"]']").val(),2);

	var totalPrice = num*guidePrice*discount/100+fee;
	if(num >0){
		$("input[name='price"+midTmp+"["+tmpId+"]']").val(totalPrice.toFixed(2));
	}else{
		$("input[name='price"+midTmp+"["+tmpId+"]']").val("0.00");
	}	
});
</script>
