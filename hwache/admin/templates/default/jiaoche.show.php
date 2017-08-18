<?php defined('InHG') or exit('Access Invalid!');?>

<div class="page">
<div class="fixed-bar">
    <div class="item-title">
      <h3>交车管理</h3>
      <ul class="tab-base">
        <li><a href="index.php?act=jiaoche&op=index&d_type=undone" <?php if($_GET['d_type']=='undone'){echo "class=current";}?>><span>待审核</span></a></li>
        <li><a href="index.php?act=jiaoche&op=index&d_type=done" <?php if($_GET['d_type']=='done'){echo "class=current";}?>><span>已审核</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <table class="table tb-type2 order">
    <tbody>
    
      <tr class="space">
        <th colspan="2">交车信息详情</th>
      </tr>
      <tr>
        <td><ul>
            <li><strong>工单编号:</strong><?php echo $output['info']['id'];?></li>
            <li> <strong>交车信息提交时间:</strong><?php echo $output['info']['create_at'];?></li>
            <li><strong>订单编号：</strong><?php echo $output['info']['order_num'];?></li>
            <li>&nbsp;</li>
            <?php 
            $brand = explode("&gt;",$output['info']['car_name']);
            ?>
            <li><strong>品牌：</strong><?php echo $brand[0];?></li>
            <li><strong>车系：</strong><?php echo $brand[1];?></li>
            <li><strong>车型规格：</strong><?php echo $brand[2];?></li>
            <li>&nbsp;</li>
            <li><strong>客户姓名：</strong><?php echo $output['allUserInfo']['buyer']['member_name'];?></li>
            <li><strong>客户称呼：</strong><?php echo $output['allUserInfo']['buyer']['member_truename'];?></li>
            <li><strong>客户电话：</strong><?php echo $output['allUserInfo']['buyer']['member_mobile'];?></li>
            <li>&nbsp;</li>
            <li><strong>售方用户名：</strong><?php echo $output['allUserInfo']['seller']['seller_name'];?></li>
            <li><strong>售方真实姓名：</strong><?php echo $output['allUserInfo']['seller']['member_truename'];?></li>
            <li><strong>售方电话：</strong><?php echo $output['allUserInfo']['seller']['member_mobile'];?></li>
            <li>&nbsp;</li>
            <li><strong>经销商名称：</strong><?php echo $output['allUserInfo']['seller']['d_name'];?></li>
            <li><strong>交车地点：</strong><?php echo $output['allUserInfo']['seller']['d_jc_place'];?></li>
            <li><strong>客户承诺上牌地区：</strong><?php echo $output['shangpai_area'];?></li>
            <li><strong>上牌方式：</strong>
            <?php echo $output['info']['shangpai']==1?'经销商上牌':'客户自己上牌';?>
            </li>
            <li><strong>状态：</strong>
            <?php 
		        if($output['info']['shangpai']==1){//经销商上牌
		        	if($output['info']['pdi_date_first']!=''){
		        		$str = '售方已提交上牌信息';
		        	}
		        	if($output['info']['user_date_first']!=''){
		        		$str = '客户已提交上牌信息';
		        	}
		        	if($output['info']['pdi_date_first']!='' && $output['info']['user_date_first']!=''){
		        		$str = '双方已提交上牌信息';
		        	}
		        	
		        }else{//客户自由上牌
		        	if($output['info']['pdi_date_first']!=''){
		        		$str = '售方已提交交车信息';
		        	}
		        	if($output['info']['user_date_first']!=''){
		        		$str = '客户已提交交车信息';
		        	}
		        	if($output['info']['pdi_date_first']!='' && $output['info']['user_date_first']!=''){
		        		$str = '双方已提交交车信息';
		        	}
		        	
		        	if($output['info']['pdi_chepai']!=''){
		        		$str = '售方已提交上牌信息';
		        	}
		        	if($output['info']['user_chepai']!=''){
		        		$str = '客户已提交上牌信息';
		        	}
		        	if($output['info']['pdi_chepai']!='' && $output['info']['user_chepai']!=''){
		        		$str = '双方已提交上牌信息';
		        	}
		        	
		        }
		        echo $str;
		        ?>
            
            </li>
            <li><strong>客户反馈交车执行情况：</strong><a href="index.php?act=jiaoche&op=get_user_jiaoche_feedback&order_num=<?php echo $output['info']['order_num']?>" target='_bank'>查看</a></li>
           <!-- 客户自己上牌超时 -->
            <?php if($output['info']['shangpai']==0 && $output['info']['user_shangpai_time']<date('Y-m-d') && empty($output['info']['user_chepai'])){?>
            <li>
            客户预计最晚上牌日期：<?php echo $output['info']['user_shangpai_time']; ?><font color="blue">(已超时)</font>
            </li>
            <li>
            &nbsp;
            </li>
            <li>
            客户本人上牌违约赔偿金额约定（客户）：<?php echo $output['info']['contract_money']; ?>
            </li>
            <li>
            客户本人上牌违约赔偿金额约定（售方）：<?php echo $output['info']['contract_money']; ?>
            </li>
            <?php }?>
          </ul></td>
      </tr>
      <tr class='space'><th colspan=2>交车信息</th></tr>
      <tr>
      <td>
	      <table style="width:900px;">
	      	<tr class='space'>
	      		<th>项目</th>
	      		<th>客户</th>
	      		<th>售方</th>
	      		<th>平台核实</th>
	      		<th class='center'>平台操作 </th>
	      	</tr>
	      	<tr>
	      		<td>车辆识别号</td>
	      		<td><?php echo $output['info']['user_vin'];?></td>
	      		<td><?php echo $output['info']['pdi_vin'];?></td>
	      		<td>
	      		<?php 
	      		if(empty($output['info']['hw_vin'])){
	      			$vin= $output['info']['user_vin']==$output['info']['pdi_vin']?$output['info']['user_vin']:'';
	      		}else{
	      			$vin = $output['info']['hw_vin'];
	      		}
	      		?>
	      		<input type='text' name='vin' style='wdith:150px;' value="<?php echo $vin;?>"></td>
	      		<td class='center' onclick="modify('vin')">修改</td>
	      	</tr>
	      	<tr>
	      		<td>发动机号</td>
	      		<td><?php echo $output['info']['user_engine_no'];?></td>
	      		<td><?php echo $output['info']['pdi_engine_no'];?></td>
	      		<td>
	      		<?php 
	      		if(empty($output['info']['hw_engine_no'])){
	      			$engine= $output['info']['user_engine_no']==$output['info']['pdi_engine_no']?$output['info']['user_engine_no']:'';
	      		}else{
	      			$engine = $output['info']['hw_engine_no'];
	      		}
	      		?>
	      		<input type='text' name='engine_no' style='wdith:150px;' value="<?php echo $engine; ?>"></td>
	      		<td class='center' onclick="modify('engine_no')">修改</td>
	      	</tr>
	      	<tr>
	      		<td>上牌地区</td>
	      		<td><?php echo $output['info']['user_shangpai_area'];?></td>
	      		<td><?php echo $output['info']['pdi_shangpai_area'];?></td>
	      		<td>
	      		<?php 
	      		if(empty($output['info']['hw_shangpai_area'])){
	      			$shangpai_area= $output['info']['user_shangpai_area']==$output['info']['pdi_shangpai_area']?$output['info']['user_shangpai_area']:'';
	      		}else{
	      			$shangpai_area = $output['info']['hw_shangpai_area'];
	      		}
	      		?>
	      		
	      		<input type='text' name='shangpai_area' style='wdith:150px;' value="<?php echo $shangpai_area; ?>"></td>
	      		<td class='center' onclick="modify('shangpai_area')">修改</td>
	      	</tr>
	      	<tr>
	      		<td>车辆用途</td>
	      		<td><?php echo $output['info']['user_useway'];?></td>
	      		<td><?php echo $output['info']['pdi_useway'];?></td>
	      		<td>
	      		<?php 
	      		if(empty($output['info']['hw_useway'])){
	      			$useway= $output['info']['user_useway']==$output['info']['pdi_useway']?$output['info']['user_useway']:'';
	      		}else{
	      			$useway = $output['info']['hw_useway'];
	      		}
	      		?>
	      		<input type='text' name='useway' style='wdith:150px;' value="<?php echo $useway;?>"></td>
	      		<td class='center' onclick="modify('useway')">修改</td>
	      	</tr>
	      	<tr>
	      		<td>上牌车主名称</td><td><?php echo $output['info']['user_regname'];?></td>
	      		<td><?php echo $output['info']['pdi_regname'];?></td>
	      		<td>
	      		<?php 
	      		if(empty($output['info']['hw_regname'])){
	      			$regname= $output['info']['user_regname']==$output['info']['pdi_regname']?$output['info']['user_regname']:'';
	      		}else{
	      			$regname = $output['info']['hw_regname'];
	      		}
	      		?>
	      		<input type='text' name='regname' style='wdith:150px;' value="<?php echo $regname;?>"></td>
	      		<td class='center' onclick="modify('regname')">修改</td>
	      	</tr>
	      	<tr>
	      		<td>牌照号码</td>
	      		<td><?php echo !empty($output['info']['user_chepai'])?implode('',unserialize($output['info']['user_chepai'])):'';?></td>
	      		<td><?php echo !empty($output['info']['pdi_chepai'])?implode('',unserialize($output['info']['pdi_chepai'])):'';?></td>
	      		<td>
	      		<?php 
	      		if(empty($output['info']['hw_chepai'])){
	      			$chepai= $output['info']['user_chepai']==$output['info']['pdi_chepai']?implode("",unserialize($output['info']['user_chepai'])):'';
	      		}else{
	      			$chepai = $output['info']['hw_chepai'];
	      		}
	      		?>
	      		<input type='text' name='chepai' style='wdith:150px;' value="<?php echo $chepai;?>"></td>
	      		<td class='center' onclick="modify('chepai')">修改</td>
	      	</tr>
	      	<?php if($output['butie']==1){?>
	      	<tr>
	      		<td>国家节能补贴约定发放时间</td>
	      		<td><?php echo $output['info']['user_butie_date'];?></td>
	      		<td><?php echo $output['info']['pdi_butie_date'];?></td>
	      		<td>
	      		<?php 
	      		if(empty($output['info']['hw_butie_date'])){
	      			$butie_date= $output['info']['user_butie_date']==$output['info']['pdi_butie_date']?$output['info']['user_butie_date']:'';
	      		}else{
	      			$butie_date = $output['info']['hw_butie_date'];
	      		}
	      		?>
	      		<input type='text' name='butie_date' style='wdith:150px;'value="<?php echo $butie_date;?>"></td>
	      		<td class='center' onclick="modify('butie_date')">修改</td>
	      	</tr>
	      	<?php }?>
	      	<tr>
	      		<td>补充证据</td>
	      		<td>
	      		<?php 
	      		if(isset($output['resupply']['1_status']) && $output['resupply']['1_status']=='N'){
					echo "<p style='color:blue;'>等待客户补充证据</p>";	      			
	      		}else{
	      			if(isset($output['resupply']['1']) && count($output['resupply']['1'])>0){
	      				foreach($output['resupply']['1'] as $k=>$v){
	      					if(!empty($v['resupply_file'])){
	      						$files = unserialize($v['resupply_file']);
	      						foreach($files as $file){
	                        		echo '<a href="'.$GLOBALS['config']['www_site_url'].'/upload/'.$file.'" target="_bank">'.ltrim($file,"file/").'</a><br>';	
	                        	}
	      					}
	      					
	      				}
	      				echo "<input type='button' value='继续补充' name='user_resupply'>";
	      			}else{
	      				echo "<input type='button' value='要求客户补充' name='user_resupply'>";
	      			}
	      		}
	      		?>
	      		
	      		</td>
	      		<td>
	      		<?php 
	      		if(isset($output['resupply']['2_status']) && $output['resupply']['2_status']=='N'){
					echo "<p style='color:blue;'>等待售方补充证据</p>";	      			
	      		}else{
	      			if(isset($output['resupply']['2']) && count($output['resupply']['2'])>0){
	      				foreach($output['resupply']['2'] as $k=>$v){
	      					if(!empty($v['resupply_file'])){
	      						$files = unserialize($v['resupply_file']);
	      						foreach($files as $file){
	                        		echo '<a href="'.$GLOBALS['config']['www_site_url'].'/upload/'.$file.'" target="_bank">'.ltrim($file,"file/").'</a>&nbsp;&nbsp;<br>';	
	                        	}
	      					}
	      					
	      				}
	      				echo "<input type='button' value='继续补充' name='pdi_resupply'>";
	      			}else{
	      				echo "<input type='button' value='要求售方补充' name='pdi_resupply'>";
	      			}
	      		}
	      		?>
	      		</td>
	      		<td colspan=2>
	      		<?php 
	      		if(isset($output['resupply']['3']) && count($output['resupply']['3'])>0){
	      			foreach($output['resupply']['3'] as $k=>$v){
	      				if(!empty($v['resupply_file'])){
	      					$files = unserialize($v['resupply_file']);
	      					foreach($files as $file){
	      						echo '<a href="'.$GLOBALS['config']['www_site_url'].'/upload/'.$file.'" target="_bank">'.ltrim($file,"file/").'</a>&nbsp;&nbsp;<a href="javascript:del_jiaoche_hw_file('.$v['id'].')">取消上传</a><br>';
	      					}
	      				}
	      		
	      			}
	      		}
	      		?>
	      		<form action='index.php?act=jiaoche&op=ajax_sub' method='post' name='item-form-add-hw-resupply' enctype="multipart/form-data">
	      		<input type='file' name='file'><input type='button' value='上传' name='add-hw-resupply-button'>
	      		<input type="hidden" name="order_num" value="<?php echo $output['info']['order_num']?>" />
    	  		<input type="hidden" name="type" value="6" />
	      		</form>
	      		</td>
	      	</tr>
	      	<!-- 客户\售方 补充证据 start -->
	      	<tr class='space' id='resupply-head' style="display:none;">
	      		<th colspan=5>需要<label id='who_do_str'>客户</label>补充以下证据</th>
	      		<input type='hidden' name='who_do' value='1'><!-- 默认客户 -->
	      	</tr>
	      	<tr id='resupply-content' style="display:none;">
	      		<td colspan=5>
	      		
	      		<table width="100%">
	      		<tr><td colspan=4>需要提交方针对以下哪个方面补充证据</td></tr>
	      		<tr>
	      			<td><input type='checkbox' name='option' value='车辆识别号'>车辆识别号</td>
	      			<td><input type='checkbox' name='option' value='发动机号'>发动机号</td>
	      			<td><input type='checkbox' name='option' value='上牌地区'>上牌地区</td>
	      			<td><input type='checkbox' name='option' value='车辆用途'>车辆用途</td>
	      		</tr>
	      		<tr>
	      			
	      			<td><input type='checkbox' name='option' value='上牌车主名称'>上牌车主名称</td>
	      			<td><input type='checkbox' name='option' value='牌照号码'>牌照号码</td>
	      			<td><input type='checkbox' name='option' value='国家节能补贴时间'>国家节能补贴时间</td>
	      			<td></td>
	      		</tr>
	      		<tr>
	      			<td colspan=4>
	      				<input type='checkbox' name='init-check'><input type="text" style="width:150px;" name='add-option'> &nbsp;&nbsp;<input type='button' name='add' value='新增'>
	      				<br><br><input type="button" name="jc_submit" value="提交">
	      			
	      			</td>
	      		</tr>
	      		</table>
	      		</td>
	      	</tr>
	      	<!-- 客户补充证据 end -->
	      </table>
      </td>
      </tr>
      <tr class='space'><th colspan=2>售方提交交车证明信息</th></tr>
      <tr><td>交车证明文件图片</td><td></td></tr>
      <tr><td colspan=2>双方签字的交车确认书：<a href="<?php echo $GLOBALS['config']['www_site_url'].'/upload/'.$output['info']['pdi_car_sure_file'];?>" target="_bank"><?php echo ltrim($output['info']['pdi_car_sure_file'],'file/'); ?></a></td></tr>
      <tr><td colspan=2>客户签字的验车单：<a href="<?php echo $GLOBALS['config']['www_site_url'].'/upload/'.$output['info']['pdi_car_validate_file'];?>" target="_bank"><?php echo ltrim($output['info']['pdi_car_validate_file'],'file/'); ?></a></td></tr>
      <?php if($output['info']['shangpai']==1){?>
      <tr><td colspan=2>机动车登记证书信息栏：<a href="<?php echo $GLOBALS['config']['www_site_url'].'/upload/'.$output['info']['pdi_car_checkin_file'];?>" target="_bank"><?php echo ltrim($output['info']['pdi_car_checkin_file'],'file/'); ?></a></td></tr>
      <?php }?>
      <tr class='space'><th colspan=2>客户本人上牌判定</th></tr>
      <tr><td>客户本人上牌判定</td><td></td></tr>
      <tr>
      	<td colspan=2>
      	<?php if(empty($output['info']['hw_shangpai_check_status'])){
      			$style="style=display:block";
			 }else{
      			if($output['info']['hw_shangpai_check_status']==1){
      				echo '<font color="red">客户违约，判定结果为：赔偿售方￥'.$output['info']['hw_shangpai_excute'].'</font>';
      			}else{
      				echo '客户不违约';
      			}
      			$style="style=display:none";
      			echo '<br><br><input type="button" value="修改判定" onclick="show_form(\'item-form-shangpai\')"><br><br>';
      	 	}
      	 ?>
      	 
      	 <form action="index.php?act=jiaoche&op=ajax_sub&id=<?php echo $output['info']['id'];?>" method='post' name='item-form-shangpai' enctype="multipart/form-data" <?php echo $style;?>>
		  <input type='radio' name='is_object' value='N'>客户未违约<br>
		  <input type='radio' name='is_object' value='Y'>客户违约<br>
		  &nbsp; &nbsp; &nbsp; &nbsp;<input type='radio' name='excute' value='0'>双方调解，退回客户相应部分的买车担保金<br>
		  &nbsp; &nbsp; &nbsp; &nbsp;<input type='radio' name='excute' value='<?php echo $output['info']['contract_money']; ?>'>客户进行赔偿-￥<?php echo $output['info']['contract_money']; ?>，售方获得补偿+￥<?php echo $output['info']['contract_money']; ?>
		  <br><br>
      	  判定流程证据：<br>
      	  <input type='file' name='file_1'><input type='button' value='新增' id='add-for-shangpai'>
      	  <br><br>
      	  <input type='button' name='sub-shangpai' value='提交'>
      	  <input type="hidden" name="act" value="jiaoche" />
    	  <input type="hidden" name="op" value="ajax_sub" />
    	  <input type="hidden" name="id" value="<?php echo $output['info']['id']?>" />
    	  <input type="hidden" name="type" value="3" />
      	</form>
      	
      	</td>
      </tr>
      <tr class='space'><th colspan=2>备注信息</th></tr>
      <tr>
      	<td colspan=2>
      		<table style="width: 100%">
      			<tr><th>编号</th><th>内容</th><th>备注人</th><th>备注时间</th></tr>
      			<?php 
      			if(count($output['note'])>0){
      				foreach($output['note'] as $note){	
      			?>
      			<tr>
      				<td><?php echo $note['id'];?></td>
      				<td><?php echo $note['note'];?></td>
      				<td><?php echo $note['noter'];?></td>
      				<td><?php echo $note['date'];?></td>
      			</tr>
      			<?php 
      				}
      			}
      			?>
      			<tr>
      				<td colspan=4>
      				<form action="" method='post' name='item-form-add-note'>
      				备注：<br><br>
      				<textarea name='note' style='width:80%;height:50px;'></textarea><br><br>
      				<input type='button' name='add-note' value='提交备注'>
      				<input type="hidden" name="act" value="jiaoche" />
			    	  <input type="hidden" name="op" value="ajax_sub" />
			    	  <input type="hidden" name="id" value="<?php echo $output['info']['id']?>" />
			    	  <input type="hidden" name="type" value="4" />
      				</td>
      			</tr>
      		</table>
      	</td>
      </tr>
      <tr>
      	<td class='center'>
      	<?php if(empty($output['info']['hw_check_status'])){?>
      		<input type='button' name='check-button' value='确认核实信息' style='background-color: orange;width:100px;height:40px;line-height:40px;color:white'>
      	<?php }else{
      		echo '<b><font color="orange">已经完成审核</font></b>&nbsp;&nbsp;&nbsp;&nbsp;审核时间'.$output['info']['hw_check_date'];
      	}?>
      	</td>
      	<td>&nbsp;</td>
      </tr>
  </table>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.form.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript">
function modify(name){
	var _id = '<?php echo $output['info']['id']?>';
	var _data = $("input[name="+name+"]").val();
	if(window.confirm('确认要更新此信息吗')){
		$.ajax({
	        url: "index.php",
	        type: "post",
	        dataType: "json",
	        data: {
	        	act:'jiaoche',
				op:'ajax_sub',
				type:1,
				field:'hw_'+name,
				val:_data,
				id:_id,
	        },
	        success: function (data) {
	            var _error_code = data.error_code;
	            var _error_msg = data.error_msg;
	            if(_error_code == 1){
	               alert(_error_msg);
	            }else{
					alert('更新成功');
					window.location.reload();
	            }
	        }
	        
		});
	}
	
	
}

$(function(){
	$("input[name=add]").click(function(){
		var _content = $('input[name="add-option"]').val();
		if(_content ==''){
			alert('新增内容不能为空');
			return false;
		}
		var _add_str = '<input type="checkbox" name="option" value="'+_content+'" checked>'+_content+'<br><br>';
		$("input[name='init-check']").before(_add_str);
		$('input[name="add-option"]').val('');
		
	})

	$("input[name=user_resupply]").click(function(){
		$("#who_do_str").html('客户');
		$("input[name='who_do']").val('1');
		$("#resupply-head").show();
		$("#resupply-content").show();

		
	})
	$("input[name=pdi_resupply]").click(function(){
		$("#who_do_str").html('售方');
		$("input[name='who_do']").val('2');
		$("#resupply-head").show();
		$("#resupply-content").show();
	})

	$("input[name='jc_submit']").click(function(){
		var _num = $("input[name=option]:checked").length;
		
		if(_num==0){
			alert('选择项不能为空');
			return false;
		}
		var _data = '';
		
		$("input[name=option]:checked").each(function(item){
			_data = _data+$(this).val()+",";
		})
		var _member= $('input[name=who_do]').val();
		var _id = '<?php echo $output['info']['id']?>';
		if(window.confirm('确认要补充此证据吗')){
			$.ajax({
		        url: "index.php",
		        type: "post",
		        dataType: "json",
		        data: {
		        	act:'jiaoche',
					op:'ajax_sub',
					type:2,
					member_type:_member,
					content:_data,
					id:_id,
					order_num:'<?php echo $output['info']['order_num'];?>',
		        },
		        success: function (data) {
		            var _error_code = data.error_code;
		            var _error_msg = data.error_msg;
		            if(_error_code == 1){
		               alert(_error_msg);
		            }else{
						alert('更新成功');
						window.location.reload();
		            }
		        }
		        
			});
		}

	})
	$("#add-for-shangpai").click(function(){
		var _num = $('form[name="item-form-shangpai"]').find('input[type=file]').length + 1;
		var _add_str = '<br><br><input type="file" name="file_'+_num+'" >';
		$(this).after(_add_str);
	})
	$("input[name='sub-shangpai']").click(function(){
		var _form = $('form[name="item-form-shangpai"]');
		if(_form.find('input[name="is_object"]:checked').length ==0){
			alert('请选择是否违约');
			return false;
		}else{
			if(_form.find('input[name="is_object"]:checked').val() =='Y' &&_form.find('input[name="excute"]:checked').length ==0){
				alert('请选择违约处理内容');
				return false;
			}
		}
		
        var options = {
        	dataType: "json",
            success: function (data) {
                
              if(data.error_code==0){
           	   alert('补充材料提交成功');
           	   setTimeout("window.location.reload()",1000);
              }else{
           	   alert('补充材料提交失败');
              }
            }
            ,
            beforeSubmit:function(){
            	
            }
            ,
            clearForm:true
        }
   	 	_form.ajaxForm(options) 
        _form.ajaxSubmit(options)
	})

	$("input[name='add-note']").click(function(){
		if($("textarea[name='note']").val()==''){
			alert('备注不能为空');
			return  false;
		}
		var _note = $("textarea[name='note']").val();
		$.ajax({
	        url: "index.php",
	        type: "post",
	        dataType: "json",
	        data: {
	        	act:'jiaoche',
				op:'ajax_sub',
				type:4,
				note:_note,
				order_num:'<?php echo $output['info']['order_num'];?>',
	        },
	        success: function (data) {
	            var _error_code = data.error_code;
	            var _error_msg = data.error_msg;
	            if(_error_code == 1){
	               alert(_error_msg);
	            }else{
					alert('更新成功');
					window.location.reload();
	            }
	        }
	        
		});
	})
	$("input[name='check-button']").click(function(){
		if(window.confirm('您确认已经核实此信息了吗')){
			$.ajax({
		        url: "index.php",
		        type: "post",
		        dataType: "json",
		        data: {
		        	act:'jiaoche',
					op:'ajax_sub',
					type:5,
					id:'<?php echo $output['info']['id'];?>',
					order_num:'<?php echo $output['info']['order_num'];?>',
		        },
		        success: function (data) {
		            var _error_code = data.error_code;
		            var _error_msg = data.error_msg;
		            if(_error_code == 1){
		               alert(_error_msg);
		            }else{
						alert('更新成功');
						window.location.reload();
		            }
		        }
		        
			});
		}
	})
	$("input[name='add-hw-resupply-button']").click(function(){
		var _form = $('form[name="item-form-add-hw-resupply"]');
		if(_form.find("input[type=file]").val()==''){
			alert('上传文件不能为空');
			return false;
		}
		var options = {
				dataType: "json",
	            success: function (data) {
	              if(data.error_code==0){
	           	   alert('补充材料提交成功');
	           	   setTimeout("window.location.reload()",1000);
	              }else{
	           	   alert('补充材料提交失败');
	              }
	            }
	            ,
	            beforeSubmit:function(){
	            	
	            }
	            ,
	            clearForm:true
	        }
	   	 	_form.ajaxForm(options) 
	        _form.ajaxSubmit(options)
	})
})

function del_jiaoche_hw_file(id){
	$.ajax({
        url: "index.php",
        type: "post",
        dataType: "json",
        data: {
        	act:'jiaoche',
			op:'ajax_sub',
			type:7,
			id:id,
        },
        success: function (data) {
            var _error_code = data.error_code;
            var _error_msg = data.error_msg;
            if(_error_code == 1){
               alert(_error_msg);
            }else{
				alert('更新成功');
				window.location.reload();
            }
        }
        
	});

}
function show_form(form_name){
	$("form[name='"+form_name+"']").show();
}

</script>
