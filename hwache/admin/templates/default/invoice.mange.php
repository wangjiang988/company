<?php defined('InHG') or exit('Access Invalid!');?>

<div class="page">
<div class="fixed-bar">
    <div class="item-title">
      <h3>发票管理</h3>
      <ul class="tab-base">
        <li><a href="index.php?act=invoice&op=index&i_type=1"><span><?php echo '待处理';?></span></a></li>
        <li><a href="index.php?act=invoice&op=index&i_type=2"><span><?php echo '完成处理';?></span></a></li>
        <li><a href="index.php?act=invoice&op=index&i_type=3"><span><?php echo '超时未开';?></span></a></li>
        <li><a href="index.php?act=invoice&op=invoice_manage_list" class="current"><span><?php echo '空白发票';?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <table class="table tb-type2 order">
    <tbody>
    <?php if($_REQUEST['invoice_type']==1){?>
      <tr class="space">
        <th colspan="15">增值税普通发票</th>
      </tr>
      <tr>
        <td colspan="2"><ul>
            <li>
            <strong>当前可用数:</strong><?php echo $output['invoiceNum']['ptfp_num'];?>
   
            </li>
            
            <li><strong>上次领票时间：</strong><?php echo '';?></li>
            <li>
            <form action="" method="post" name='ptfp-add-num-form'>
            <strong>本次增加数:</strong>
            <span><input type="text" name="add_num" id="ptfp-add-num" style="width:50px;"> 份&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name='tj_ptfp' value='提交'></span>
            <input type="hidden" name='todo' value="ptfp-add-num">
            <input type="hidden" name='act' value="invoice">
            <input type="hidden" name='op' value="invoice_manage">
            <input type="hidden" name='1' value="invoice_type">
            </form>
            </li>
            <li> <strong>&nbsp;</strong>&nbsp;</li>
            <li> <strong>&nbsp;</strong>&nbsp;</li>
            <li>
            <form action="" method="post" name='ptfp-period-form'>
            <strong>&nbsp;&nbsp;领票周期：</strong>
            <input type="text" name="ptfp_period" id="ptfp_period" style="width:50px"  disabled value="<?php echo $output['invoiceNum']['ptfp_period'];?>"> 天  &nbsp;&nbsp;<a href="#" id='i-modify-ptfp-period'>修改</a>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name='tj_ptfp_period' value='提交' style="display:none;"></li>
            <input type="hidden" name='todo' value="ptfp-period">
            <input type="hidden" name='act' value="invoice">
            <input type="hidden" name='op' value="invoice_manage">
            <input type="hidden" name='1' value="invoice_type">
            </form>
            <li>
            <form action="" method="post" name='ptfp-max-form'>
            <strong>每次可领份数:</strong>
            <span><input type="text" name="ptfp_max" id="ptfp_max" style="width:50px"  disabled value="<?php echo $output['invoiceNum']['ptfp_max'];?>"> 份 &nbsp;&nbsp; <a href="#" id='i-modify-ptfp-max'>修改</a>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name='tj_ptfp_max' value='提交' style="display:none;"></span>
            <input type="hidden" name='todo' value="ptfp-max">
            <input type="hidden" name='act' value="invoice">
            <input type="hidden" name='op' value="invoice_manage">
            <input type="hidden" name='1' value="invoice_type">
            </form>
            </li>
            
          </ul></td>
      </tr>
      <?php }else{?>
      <tr class="space">
        <th colspan="2">增值税专用发票</th>
      </tr>
      <tr>
        <td><ul>
            <li>
            <strong>当前可用数:</strong><?php echo $output['invoiceNum']['zyfp_num'];?>
   
            </li>
            
            <li><strong>上次领票时间：</strong><?php echo '';?></li>
            <li>
            <form action="" method="post" name='zyfp-add-num-form'>
            <strong>本次增加数:</strong>
            <span><input type="text" name="add_num" id="zyfp-add-num" style="width:50px;"> 份&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name='tj_zyfp' value='提交'></span>
            <input type="hidden" name='todo' value="zyfp-add-num">
            <input type="hidden" name='act' value="invoice">
            <input type="hidden" name='op' value="invoice_manage">
            <input type="hidden" name='2' value="invoice_type">
            </form>
            </li>
            <li> <strong>&nbsp;</strong>&nbsp;</li>
            <li> <strong>&nbsp;</strong>&nbsp;</li>
            <li>
            <form action="" method="post" name='zyfp-period-form'>
            <strong>&nbsp;&nbsp;领票周期：</strong>
            <input type="text" name="zyfp_period" id="zyfp_period" style="width:50px"  disabled value="<?php echo $output['invoiceNum']['zyfp_period'];?>"> 天  &nbsp;&nbsp;<a href="#" id='i-modify-zyfp-period'>修改</a>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name='tj_zyfp_period' value='提交' style="display:none;"></li>
            <input type="hidden" name='todo' value="zyfp-period">
            <input type="hidden" name='act' value="invoice">
            <input type="hidden" name='op' value="invoice_manage">
            <input type="hidden" name='2' value="invoice_type">
            </form>
            <li>
            <form action="" method="post" name='zyfp-max-form'>
            <strong>每次可领份数:</strong>
            <span><input type="text" name="zyfp_max" id="zyfp_max" style="width:50px"  disabled value="<?php echo $output['invoiceNum']['zyfp_max'];?>"> 份 &nbsp;&nbsp; <a href="#" id='i-modify-zyfp-max'>修改</a>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name='tj_zyfp_max' value='提交' style="display:none;"></span>
            <input type="hidden" name='todo' value="zyfp-max">
            <input type="hidden" name='act' value="invoice">
            <input type="hidden" name='op' value="invoice_manage">
            <input type="hidden" name='2' value="invoice_type">
            </form>
            </li>
            
          </ul></td>
      </tr>
      <?php }?>
  </table>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript">
$(function(){
    
    $('#i-modify-ptfp-period').click(function(){
    	$(this).hide();
    	$("input[name='ptfp_period']").attr("disabled",false);
    	$("input[name='tj_ptfp_period']").show();
    	
    });
    $('#i-modify-ptfp-max').click(function(){
    	$(this).hide();
    	$("input[name='ptfp_max']").attr("disabled",false);
    	$("input[name='tj_ptfp_max']").show();
    	
    });
    $('#i-modify-zyfp-period').click(function(){
    	$(this).hide();
    	$("input[name='zyfp_period']").attr("disabled",false);
    	$("input[name='tj_zyfp_period']").show();
    	
    });
    $('#i-modify-zyfp-max').click(function(){
    	$(this).hide();
    	$("input[name='zyfp_max']").attr("disabled",false);
    	$("input[name='tj_zyfp_max']").show();
    	
    });

    $("input[name='tj_ptfp']").click(function(){
		if(parseInt($("#ptfp-add-num").val()) <=0 || $("#ptfp-add-num").val() ==''){
			alert('增值税普通发票增加数量必须是正整数');
		}else{
			if(window.confirm('确定新增 增值税普通发票 吗？')){
				$("form[name='ptfp-add-num-form']").submit();
			}
		}
    })
    $("input[name='tj_ptfp_period']").click(function(){
		if( $("#ptfp_period").val() ==''){
			alert('发票周期不能为空');
		}else{
			if(window.confirm('确定修改增值税普通发票周期吗？')){
				$("form[name='ptfp-period-form']").submit();
			}
		}
    })
    $("input[name='tj_ptfp_max']").click(function(){
		if( $("#ptfp_max").val() =='' || $("#ptfp_max").val()==0){
			alert('值税普通发票每次可领份数不能为空或零');
		}else{
			if(window.confirm('确定修改增值税普通发票每次可领份数吗？')){
				$("form[name='ptfp-max-form']").submit();
			}
		}
    })

    $("input[name='tj_zyfp']").click(function(){
		if(parseInt($("#zyfp-add-num").val()) <=0 || $("#zyfp-add-num").val() ==''){
			alert('增值税专用发票增加数量必须是正整数');
		}else{
			if(window.confirm('确定新增 增值专用通发票 吗？')){
				$("form[name='zyfp-add-num-form']").submit();
			}
		}
    })
    $("input[name='tj_zyfp_period']").click(function(){
		if( $("#zyfp_period").val() ==''){
			alert('增值税专用发票周期不能为空');
		}else{
			if(window.confirm('确定修改增值税专用发票周期吗？')){
				$("form[name='zyfp-period-form']").submit();
			}
		}
    })
    $("input[name='tj_zyfp_max']").click(function(){
		if( $("#zyfp_max").val() =='' || $("#zyfp_max").val()==0){
			alert('值税专用发票每次可领份数不能为空或零');
		}else{
			if(window.confirm('确定修改增值税专用发票每次可领份数吗？')){
				$("form[name='zyfp-max-form']").submit();
			}
		}
    })
    
});
</script>
