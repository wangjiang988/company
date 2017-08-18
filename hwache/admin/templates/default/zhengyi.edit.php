<?php defined('InHG') or exit('Access Invalid!');

$customer_wenti = ['车辆状况不符','移交文件或物品不符','车辆质量瑕疵','附加条件变卦或临时增加','服务人员专业素质或态度不佳','其他'];
$seller_wenti = ['客户拖延付款','客户未在约定时间前往提车','其他'];
if($output['dispute']['buy_id']==$output['dispute']['member_id']){
	$dispute_memberStr = '买方';
	$defend_memberStr = '售方';
	$wenti = $customer_wenti;
	$objectValue = 1;//售方违约
}else{
	$dispute_memberStr = '售方';
	$defend_memberStr = '买方';
  	$wenti = $seller_wenti;
  	$objectValue = 2;//买方违约
}	
$dispute_resupply_evidence = !empty($output['dispute']['resupply_evidence'])?unserialize($output['dispute']['resupply_evidence']):array();
$defend_resupply_evidence = !empty($output['dispute']['defend_resupply_evidence'])?unserialize($output['dispute']['defend_resupply_evidence']):array();

$dispute_date_count = $output['dispute']['resupply_date_count'];
$defend_date_count = $output['dispute']['defend_resupply_date_count'];
//时限显示赋值
if($dispute_date_count!=''){
	$days_dispute = floor($dispute_date_count/24);
	$hour_dispute = $dispute_date_count%24;
}else{
	$days_dispute = '';
	$hour_dispute = '';
}
if($defend_date_count!=''){
	$days_defend = floor($defend_date_count/24);
	$hour_defend = $defend_date_count%24;
}else{
	$days_defend = '';
	$hour_defend = '';
}

?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>争议管理</h3>
      <ul class="tab-base">
        <li><a href="index.php?act=zhengyi&op=index&d_type=undone"><span><?php echo '待处理';?></span></a></li>
        <li><a href="index.php?act=zhengyi&op=index&d_type=done"><span><?php echo '已处理';?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
    
    <table class="table tb-type2">
      <tbody>
      	<tr class="noborder">
          <td class="vatop rowform">订单号：<?php echo $output['dispute']['order_num'];?></td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="space" style="background: rgb(251, 251, 251);">
          <th colspan=2>争议内容</th>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">提交方：<?php echo $dispute_memberStr;?></td>
          <td class="vatop tips">提交时间:<?php echo $output['dispute']['createat'];?></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">提交内容：</td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder" style="background:#EEE">
          <td colspan=2><?php echo implode(unserialize($output['dispute']['problem']),'&nbsp;&nbsp;&nbsp;&nbsp;、'); ?>&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:orange;'><?php echo $output['dispute']['resupply'];?></span></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">提交证据：</td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td colspan=2>
          <?php foreach($output['evidence']['dispute'] as $k =>$v){?>
          <a href="<?php echo $GLOBALS['config']['www_site_url'].'/upload/evidence/'.$v['urls'];?>" target="_bank"><?php echo $v['urls'];?></a>&nbsp;&nbsp;&nbsp;
          <?php 
         		}
          		foreach($dispute_resupply_evidence as $k1 =>$v1){
          ?>
          <a href="<?php echo $GLOBALS['config']['www_site_url'].'/upload/evidence/'.$v1;?>" target="_bank" style='color:orange'><?php echo $v1;?></a>&nbsp;&nbsp;&nbsp;
          <?php 
          		}
          ?>
          </td>
        </tr>
        <?php if($output['dispute']['dispute_hejie']!=''){?>
        <tr class="noborder">
          <td class="vatop rowform">和解内容：<?php echo $output['dispute']['dispute_hejie'];?></td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">和解内容提交时间：<?php echo $output['dispute']['dispute_hejie_date'];?></td>
          <td class="vatop tips"></td>
        </tr>
        <?php }?>
		<tr class="space">
          <th colspan="2">提交方补充证据</th>
        </tr>
        <tr class="noborder">
          <td style="border-right: 1px dotted #CCC">
          <?php if(empty($output['dispute']['resupply'])){?>
          <form action='' method='post' name='item-form-dispute-1'>
         <?php foreach($wenti as $k=>$v){?>
         	<input type='checkbox' value='<?php echo $v;?>' name='resupply[]'><?php echo $v;?><br>
         <?php }?>
         	<input type='checkbox' value='dispute_other_wenti' name='resupply[]'><input type='text' name='dispute_other_wenti' style="width:250px;">
          	<input type='hidden' value='resupply' name='up_field'> 
          	<input type='hidden' value='<?php echo $output['dispute']['id'];?>' name='dispute_id'>
          	<input name='act' value='zhengyi' type='hidden'>
          	<input name='op' value='ajax_sub' type='hidden'>
          	<input name='type' value='1' type='hidden'>
          </form>
          <?php }?>
          
          <?php if(empty($output['dispute']['resupply'])){?>
          	<a href="JavaScript:sub_form('item-form-dispute-1',1);" class="btn" ><span>提交方补充证据</span></a>
          	<?php }else{?>
          	已要求补充“<span style="color:orange;"><?php echo $output['dispute']['resupply'];?></span>”证据
          		<?php 
          			if($dispute_date_count!=''){
          				echo "<br><br><span style='color:blue'>倒计时：".$days_dispute."天".$hour_dispute." 小时</span>";
          			}
          			?>
          	
          	<?php }?>
          	
          </td>
          <td>
          <!-- 有提交补充证据 -但是提出方还没有提交- 可以修改时限 start -->
          <?php 
          if(!empty($output['dispute']['resupply']) && $output['dispute']['resupply_evidence']==''){
          ?>
          <form action='' method='post' name='item-form-dispute-2'>
          <?php 
          if($dispute_date_count!=''){
          		echo "<span style='color:blue'>原倒计时：".$days_dispute."天".$hour_dispute." 小时</span><br><br>";
          }
         ?>
         倒计时设定：<input style='width:30px;' name='day'> 天 <input style='width:30px;' name='hour'> 小时 （如果都为空则，表示不设定时限）
          	<input type='hidden' value='resupply_date_count' name='up_field'>
          	<input type='hidden' value='<?php echo $output['dispute']['id'];?>' name='dispute_id'>
         	<input name='act' value='zhengyi' type='hidden'>
          	<input name='op' value='ajax_sub' type='hidden'>
          	<input name='type' value='2' type='hidden'>
          </form>
          <a href="JavaScript:sub_form('item-form-dispute-2',2);" class="btn" ><span>修改时限</span></a>
          <?php }?>
           <!-- 有提交补充证据 -但是提出方还没有提交- 可以修改时限 end -->
          </td>
        </tr>
<!-- 如果有申辩 才显示以下内容 -->     
<?php if($output['dispute']['defend_id']>0){?>
        <tr class="space">
          <th colspan="2">申辩内容</th>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">申辩方：<?php if($output['dispute']['buy_id']==$output['dispute']['member_id']){echo '售方';}else{echo '买方';}?></td>
          <td class="vatop tips">申辩时间:<?php echo $output['dispute']['defend_date'];?></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">申辩内容：</td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder" style="background:#EEE">
          <td colspan=2><?php echo $output['dispute']['defend_content']; ?>
          &nbsp;&nbsp;&nbsp;&nbsp;<span style='color:orange;'><?php echo $defend_resupply;?></span>
          </td>
        </tr>

        <tr class="noborder">
          <td class="vatop rowform">提交证据：</td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td colspan=2>
          <?php foreach($output['evidence']['defend'] as $k =>$v){?>
          <a href="<?php echo $GLOBALS['config']['www_site_url'].'/upload/evidence/'.$v['urls'];?>" target="_bank"><?php echo $v['urls'];?></a>&nbsp;&nbsp;&nbsp;
          <?php 
         		}
          		foreach($defend_resupply_evidence as $k1 =>$v1){
          ?>
          <a href="<?php echo $GLOBALS['config']['www_site_url'].'/upload/evidence/'.$v1;?>" target="_bank" style='color:orange'><?php echo $v1;?></a>&nbsp;&nbsp;&nbsp;
          <?php 
          		}
          ?>
          </td>
        </tr>
        <?php if($output['dispute']['defend_hejie']!=''){?>
        <tr class="noborder">
          <td class="vatop rowform">和解内容：<?php echo $output['dispute']['defend_hejie'];?></td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">和解内容提交时间：<?php echo $output['dispute']['defend_hejie_date'];?></td>
          <td class="vatop tips"></td>
        </tr>
        <?php }?>
        <tr class="space">
          <th colspan="2">申辩方补充证据</th>
        </tr>
        <tr class="noborder">
          <td style="border-right: 1px dotted #CCC">
          
          
          <?php if(empty($output['dispute']['defend_resupply'])){?>
          <form action='' method='post' name='item-form-defend-1'>
         <?php foreach($wenti as $k=>$v){?>
         	<input type='checkbox' value='<?php echo $v;?>' name='resupply[]'><?php echo $v;?><br>
         <?php }?>
         	<input type='checkbox' value='dispute_other_wenti' name='resupply[]'><input type='text' name='dispute_other_wenti' style="width:250px;">
          	<input type='hidden' value='resupply' name='up_field'>
          	<input type='hidden' value='<?php echo $output['dispute']['defend_id'];?>' name='defend_id'>
          	<input name='act' value='zhengyi' type='hidden'>
          	<input name='op' value='ajax_sub' type='hidden'>
          	<input name='type' value='1' type='hidden'>
          </form>
          
          	<a href="JavaScript:sub_form('item-form-defend-1',1);" class="btn" ><span>提交方补充证据</span></a>
          	<?php }else{?>
          	已要求补充“<span style="color:orange;"><?php echo $output['dispute']['defend_resupply'];?></span>”证据
          		<?php 
          			if($defend_date_count!=''){
          				echo "<br><br><span style='color:blue'>倒计时：".$days_defend."天".$hour_defend." 小时</span>";
          			}
          			?>
          	
          	<?php }?>
          	
          </td>
          <td>
          <!-- 有提交补充证据 -但是申辩方还没有提交- 可以修改时限start -->
          <?php 
          if(!empty($output['dispute']['defend_resupply']) && $output['dispute']['defend_resupply_evidence']==''){
          ?>
          <form action='' method='post' name='item-form-defend-2'>
          <?php 
          if($defend_date_count!=''){
          		echo "<span style='color:blue'>原倒计时：".$days_defend."天".$hour_defend." 小时</span><br><br>";
          }
         ?>
         倒计时设定：<input style='width:30px;' name='day'> 天 <input style='width:30px;' name='hour'> 小时 （如果都为空则，表示不设定时限）
          	<input type='hidden' value='resupply_date_count' name='up_field'>
          	<input type='hidden' value='<?php echo $output['dispute']['defend_id'];?>' name='defend_id'>
         	<input name='act' value='zhengyi' type='hidden'>
          	<input name='op' value='ajax_sub' type='hidden'>
          	<input name='type' value='2' type='hidden'>
          </form>
          <br>
          <a href="JavaScript:sub_form('item-form-defend-2',2);" class="btn" ><span>修改时限</span></a>
          <?php }?>
          <!-- 有提交补充证据 -但是申辩方还没有提交- 可以修改时限 end -->
          </td>
        </tr>
        
        <tr class="space">
          <th >调解内容</th>
          <th >更新调解方案</th>
        </tr>
        <tr class="noborder">
          <td width='360'>
          <?php if($output['dispute']['itemid']==''){?>
        <form action='index.php' method='post' name='item-form-tiaojie' enctype="multipart/form-data">
        <input name='act' value='zhengyi' type='hidden'>
        <input name='op' value='ajax_sub' type='hidden'>
         <input type="hidden" name="type" value="3" />
    	<input type="hidden" name="dispute_id" value="<?php echo $output['dispute']['id']; ?>">
    	<input type="hidden" name="defend_id" value="<?php echo $output['dispute']['defend_id']; ?>">
    	<input type="hidden" name="order_num" value="<?php echo $output['dispute']['order_num']; ?>">
          	平台调解建议
          <input type='text'  name="content" class="tarea">
		<br><br>
          	调解留存证据:<br>
          	<input type="file" name='evidence_0'>
          	 <select name='evidence_0_tb'>
	          <option value="0">不同步</option>
	          <option value="1">同步到争议方</option>
	          <option value="2">同步到申辩方</option>
	          </select>
           <input type='button' value="新增">
			<br><br>
         	 平台调解备注
          <textarea  name="note" class="tarea"></textarea><br><br>
          <a href="JavaScript:void(0);" class="btn" id="submitBtn"><span><?php echo $lang['nc_submit'];?></span></a>
          </form>
          <?php }else{
          	if(!empty($output['dispute']['mediate_evidence'])){
          		$mediate_evidence=unserialize($output['dispute']['mediate_evidence']);
          	}else{
          		$mediate_evidence = array();
          	} 
          	?>
          平台调解建议1：<?php echo $output['dispute']['mediate_content'];?><br><br>
          平台调解留存证据1：
          <?php 
			foreach($mediate_evidence as $k=>$v){
				if($v['tb']==0){
					$strTb = '(不同步)&nbsp;&nbsp;';
				}elseif($v['tb']==1){
					$strTb = '(同步到争议方)&nbsp;&nbsp;';
				}elseif($v['tb']==2){
					$strTb = '(同步到申辩方)&nbsp;&nbsp;';
				}else{
					$strTb = '';
				}
				  echo '<br><a href="'.WWW_SITE_URL.'/upload/evidence/'.$v['file'].'" target="_bank">',$v['file'],'</a>'.$strTb.'<a href="javascript:del_mediate_evidence('.$output['dispute']['itemid'].','.$k.',\'N\')">取消上传</a><br>';
			}
		?>
          <br><br>
          平台调解备注1：<?php echo $output['dispute']['note'];?><br><br>
          平台调解人1：<?php echo $output['dispute']['tiaojie_operator'];?><br><br>
          平台调解时间1：<?php echo $output['dispute']['mediate_date'];?><br><br>
          	<?php if($output['dispute']['mediate_status']==0){?>
           <a href="JavaScript:void(0);" class="btn" ><span>等待客户确认调解建议</span></a>
           <?php }elseif($output['dispute']['mediate_status']==1){?>
           	<a href="JavaScript:void(0);" class="btn" ><span>客户不同意该方案</span></a>
           <?php }elseif($output['dispute']['mediate_status']>=2){?>
           	<a href="JavaScript:void(0);" class="btn" ><span>该方案已经完成或则转入更新方案</span></a>
           <?php }?>
           
           <hr  style="border: 1px dotted #CCC">
           		<?php 
           		$i=2;
           		foreach($output['mediate_assistant'] as $k=>$v){
           			if(!empty($v['m_evidence'])){
           				$v['m_evidence']=unserialize($v['m_evidence']);
           			}else{
           				$v['m_evidence'] = array();
           			}
           		?>
	           		平台调解建议<?=$i?>：<?php echo $v['m_content'];?><br><br>
	           		
				          平台调解留存证据<?=$i?>：
				    <?php 
				    foreach($v['m_evidence'] as $k1=>$v1){
					    if($v1['tb']==0){
							$strTb = '(不同步)&nbsp;&nbsp;';
						}elseif($v1['tb']==1){
							$strTb = '(同步到争议方)&nbsp;&nbsp;';
						}elseif($v1['tb']==2){
							$strTb = '(同步到申辩方)&nbsp;&nbsp;';
						}else{
							$strTb = '';
						}
					 	 echo '<br><a href="'.WWW_SITE_URL.'/upload/evidence/'.$v1['file'].'" target="_bank">',$v1['file'],'</a>'.$strTb.'<a href="javascript:del_mediate_evidence('.$output['dispute']['itemid'].','.$k1.','.$v['id'].')">取消上传</a><br>';
					}
				    ?>
				          
				          
				    <br>
				          
				          平台调解备注<?=$i?>：<?php echo $v['m_note'];?><br><br>
				          平台调解人<?=$i?>：<?php echo $v['tiaojie_operator'];?><br><br>
				          平台调解时间<?=$i?>：<?php echo $v['m_create_at'];?><br><br>
				    <?php if($v['m_status']==0){?>
		           <a href="JavaScript:void(0);" class="btn" ><span>等待客户确认调解建议</span></a>
		           <?php }elseif($v['m_status']==1){?>
		           	<a href="JavaScript:void(0);" class="btn" ><span>客户不同意该方案</span></a>
		           <?php }elseif($v['m_status']>=2){?>
		           	<a href="JavaScript:void(0);" class="btn" ><span>该方案已经完成或转入更新方案</span></a>
		           <?php }?>
				    <hr  style="border: 1px dotted #CCC">
           		<?php 
           			$i++;
           		}
           		if($output['dispute']['mediate_status'] >=1  && $output['dispute']['mediate_team_ids']==''){
           		?>
           		
             <a href="?act=zhengyi&op=judgment&dispute_id=<?=$output['dispute']['id']?>" class="btn" ><span>进入平台裁判</span></a>
          <?php 
           		
           		}
          }
           	?>
          
        	</td>
        	
        	<td style="border-left: 1px  dotted #CCC;vertical-align:bottom">
        	<!-- 更新调解方案start -->
        	<?php if($output['dispute']['mediate_status']>=1 && $output['dispute']['mediate_status']<4){?>
        	<form action='index.php' method='post' name='item-form-tiaojie' enctype="multipart/form-data">
	        <input name='act' value='zhengyi' type='hidden'>
	        <input name='op' value='ajax_sub' type='hidden'>
	         <input type="hidden" name="type" value="4" />
	        <input type="hidden" name="mediate_id" value="<?php echo $output['dispute']['itemid']; ?>">
	    	<input type="hidden" name="dispute_id" value="<?php echo $output['dispute']['id']; ?>">
	    	<input type="hidden" name="defend_id" value="<?php echo $output['dispute']['defend_id']; ?>">
	    	<input type="hidden" name="order_num" value="<?php echo $output['dispute']['order_num']; ?>">
	          	平台调解建议<br>
	          <input type='text'  name="content" class="tarea">
			<br><br>
	          	调解留存证据:<br><input type="file" name='evidence_0'>
	          <select name='evidence_0_tb'>
	          <option value="0">不同步</option>
	          <option value="1">同步到争议方</option>
	          <option value="2">同步到申辩方</option>
	          </select>
	          <input type='button' value="新增">
				<br><br>
	         	 平台调解备注<br>
	          <textarea  name="note" class="tarea"></textarea><br><br>
	          <a href="JavaScript:void(0);" class="btn" id="submitBtn"><span>提交</span></a>
	          </form>
	          <?php }?>
        	  <!-- 更新调解方案end -->
        	</td>
        </tr>
        
        <!-- 裁判团提交意见start -->
        <?php if($output['dispute']['mediate_status']>=3 && count($output['users'])>0){?>
        <tr class="space">
          <th colspan=2>平台判定</th>
        </tr>
        <tr class="noborder">
          <td colspan=2>裁判团意见 &nbsp; &nbsp; &nbsp; &nbsp;
          <a href="?act=zhengyi&op=judgment&dispute_id=<?=$output['dispute']['id']?>">选择裁判团</a>
          </td>
        </tr>
        <tr class="noborder">
          <td colspan=2>
          	<table style="width:800px;">
		          <tr>
			          <td width="50px">部门</td>
			          <td width="50px">姓名</td>
			          <td width="150px">观点</td>
			          <td width="400px">依据</td>
			          <td width="150px">提交时间</td>
		          </tr>
		          <?php 
		          $all_team_ids_array = explode(",",$output['dispute']['mediate_team_ids']);
		          $advice_tj = array($objectValue=>0,3=>0);
		          foreach($output['users'] as $k=>$v){
		          	if(isset($output['users_advice'][$v['admin_id']])){
		          		$key=$output['users_advice'][$v['admin_id']]['m_advice'];
		          		$advice_tj[$key]+=1;
		          		if($output['users_advice'][$v['admin_id']]['m_advice']==1){
		          			$obj_str = '售方违约';
		          		}elseif($output['users_advice'][$v['admin_id']]['m_advice']==2){
		          			$obj_str = '买方违约';
		          		}else{
		          			$obj_str = '都不违约';
		          		}
		          	}
		          ?>
		          <tr>
		          	<td><?php echo $v['gname'];?></td>
		          	<td><?php echo $v['admin_truename'];?></td>
		          	<td><?php echo isset($output['users_advice'][$v['admin_id']])?$obj_str:'正在提交中';?></td>
		          	<td><?php echo isset($output['users_advice'][$v['admin_id']])?$output['users_advice'][$v['admin_id']]['m_content']:'正在提交中';?></td>
		          	<td><?php echo isset($output['users_advice'][$v['admin_id']])?$output['users_advice'][$v['admin_id']]['m_date']:'正在提交中';?></td>
		          </tr>
		          
		          <?php }?>
		          <tr><td colspan=5>
		          	认为<?php echo $defend_memberStr;?>违约的：<?php echo  $advice_tj[$objectValue];?>
		          	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		          	认为<?php echo $defend_memberStr;?>不违约的：<?php echo  $advice_tj[3];?>
		          	</td>
		          </tr>
		          <?php 
		          $admin_id = $output['admin_info']['id'];
		          if(in_array($admin_id,$all_team_ids_array) && !isset($output['users_advice'][$admin_id])){
		          ?>
		          <tr>
		          	<td colspan=5>
		          	<form action='' method="post" name='item-form-advice'>
		          	<input name='act' value='zhengyi' type='hidden'>
	        		<input name='op' value='ajax_sub' type='hidden'>
	         		<input type="hidden" name="type" value="6" />
	       			<input type="hidden" name="mediate_id" value="<?php echo $output['dispute']['itemid']; ?>">
		          	观点：
		          	<select name='m_advice'>
		          		<option value="<?php echo $objectValue;?>"><?php echo $defend_memberStr;?>违约</option>
		          		<option value="3" selected><?php echo $defend_memberStr;?>不违约</option>
		          	</select>
		          	&nbsp; 依据：<input type="text" name='m_evidence' style="width: 550px;">
		          	</form>
		          	</td>
		          </tr>
		          <tr class="noborder">
		          	<td class='center' colspan=4>
		          		<a href="JavaScript:void(0);" class="btn" id='tj_advice'><span>提交</span></a>
		          	</td>
		          </tr>
		          <?php }?>
          		</table>
          </td>
        </tr>
        <?php if(count($output['users'])>0 && count($output['users']) == count($output['users_advice'])  ){?>
        <tr class="space">
          <th colspan=2>判定结果</th>
        </tr>
        <tr>
          <td colspan=2>
          <!-- 提交最终判定start -->
          <?php if($output['dispute']['breaker']==0){?>
          <form action="" name="item-form-final-advice">
          	<table style='width: 600px;'>
          		<tr>
          			<td width="120px;">判定结论</td>
          			<td>
          				<select name='breaker'>
          					<option value="<?php echo $objectValue;?>" <?php if($advice_tj[$objectValue]>$advice_tj[3]){echo 'selected';}?>><?php echo $defend_memberStr;?>违约</option>
          					<option value='3' <?php if($advice_tj[$objectValue]<$advice_tj[3]){echo 'selected';}?>><?php echo $defend_memberStr;?>不违约</option>
          				</select>
          			</td>
          		</tr>
          		<tr>
          			<td width="120px;">判定依据</td>
          			<td>
          				<textarea style="width: 100%;height:50px;" name='breaker_content'></textarea>
          			</td>
          		</tr>
          		<tr>
          			<td width="120px;">提出方（<?php echo $dispute_memberStr;?>）</td>
          			<td>
          				<input type="checkbox" name='dispute_needtodo[<?php echo urlencode('获得歉意补偿')?>]' value='<?php echo '499';?>'>获得歉意补偿：499.00元<br>
          				<input type="checkbox" name='dispute_needtodo[<?php echo urlencode('获得客户买车担保金利息补偿')?>]' value='<?php echo '21';?>'>获得客户买车担保金利息补偿（2016-04-02至2016-05-19）：21.00元<br>
          				<input type='checkbox' name='dispute_' id='dispute_add_for'>
          				追加项目<input type='text' style='width:200px;' id="dispute_add_project">
          				追加金额<input type='text' style='width:50px;' id="dispute_add_money">
          				<input type='button' name="add_option" id='dispute_add' value='追加'>
          			</td>
          		</tr>
          		<tr>
          			<td width="120px;">申辩方（<?php echo $defend_memberStr;?>）</td>
          			<td>
          				<input type="checkbox" name='defend_needtodo[<?php echo urlencode('歉意补偿')?>]' value='<?php echo '-499';?>'>歉意金补偿：-499.00元<br>
          				<input type="checkbox" name='defend_needtodo[<?php echo urlencode('获得客户买车担保金利息补偿')?>]' value='<?php echo '-21';?>'>客户买车担保金利息补偿（2016-04-02至2016-05-19）：-21.00元<br>
          				<?php 
          				$service_price = $output['service_price'];
          				?>
          				<input type="checkbox" name='defend_needtodo[<?php echo urlencode('华车平台损失赔偿')?>]' value='<?php echo '-'.abs($service_price['platform']-$service_price['seller']);?>'>华车平台损失赔偿(华车服务费<?php echo $service_price['platform'];?>-售方服务费<?php echo $service_price['seller'];?>)：<?php echo '-'.abs($service_price['platform']-$service_price['seller']);?>元<br>
          				<input type='checkbox' name='defend_' id='defend_add_for'>
          				追加项目<input type='text' style='width:200px;' id="defend_add_project">
          				追加金额<input type='text' style='width:50px;' id="defend_add_money">
          				<input type='button' name="add_option" id='defend_add' value='追加'>
          			</td>
          		</tr>
          		<tr>
          		<td colspan=2>
          			<input type='button'  id='tj_final_advice' value='判定并继续执行'>
          		</td>
          		</tr>
          	</table>
		   <input name='act' value='zhengyi' type='hidden'>
	       <input name='op' value='ajax_sub' type='hidden'>
	       <input type="hidden" name="type" value="7" />
	       <input type="hidden" name="mediate_id" value="<?php echo $output['dispute']['itemid']; ?>">
	       <input type="hidden" name="order_num" value="<?php echo $output['dispute']['order_num']; ?>">
          </form>
          <!-- 提交最终判定end -->
          <?php }else{?>
          <!-- 提交最终判定显示start -->
          <table style='width: 600px;'>
          		<tr>
          			<td width="120px;">判定结论</td>
          			<td>
          				<?php 
          				if($output['dispute']['breaker']==1){
          					echo "售方违约";
          				}elseif($output['dispute']['breaker']==2){
          					echo "客户违约";
          				}elseif($output['dispute']['breaker']==3){
          					echo "都不违约";
          				}
          				?>
          			</td>
          		</tr>
          		<tr>
          			<td width="120px;">判定依据</td>
          			<td>
          				<textarea style="width: 100%;height:50px;" name='breaker_content' disabled><?php echo $output['dispute']['breaker_content'];?></textarea>
          			</td>
          		</tr>
          		<?php 
          		if($output['dispute']['breaker_excute']!=''){
          			$breaker_excute = unserialize($output['dispute']['breaker_excute']);
          		}else{
          			$breaker_excute['dispute'] = array();
          			$breaker_excute['defend'] = array();
          		}
          		
          		?>
          		<tr>
          			<td width="120px;">提出方（<?php echo $dispute_memberStr;?>）</td>
          			<td>
          				<?php 
          				foreach($breaker_excute['dispute'] as $k=>$v){
          					echo $v['title'].":".$v['money']."<br>";
          				}
          				?>
          			</td>
          		</tr>
          		<tr>
          			<td width="120px;">申辩方（<?php echo $defend_memberStr;?>）</td>
          			<td>
          				<?php 
          				foreach($breaker_excute['defend'] as $k=>$v){
          					echo $v['title'].":".$v['money']."<br>";
          				}
          				?>
          			</td>
          		</tr>
          		<tr>
          		<td colspan=2>
          			<input type='button'   value='已经判定并继续执行'>
          		</td>
          		</tr>
          	</table>
          <!-- 提交最终判定显示end -->
          <?php }?>
          </td>
        </tr>
        <?php }?>
        <?php }?>
        <!-- 裁判团提交意见end -->
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="15" ></td>
        </tr>
      </tfoot>
<?php }?>
    </table>
</div>
<script type="text/javascript">
$(function(){
	$("input[type=button][value='新增']").click(function(){
		var length = $("input[type=file]").length;
		var _select ='<select name="evidence_'+length+'_tb"><option value="0">不同步</option><option value="1">同步到争议方</option><option value="2">同步到申辩方</option></select>';
		$(this).after("<br><br><input type='file' name='evidence_"+length+"'>"+_select);
	})
	$("#submitBtn").click(function(){
		var _form = $("form[name='item-form-tiaojie']")
		if(_form.find("input[name=content]").val() ==''){
			alert('调解内容不能为空');
			return false;
		}
		_form.submit();
		return false;
		
	});

	$("#tj_advice").click(function(){
		var _form = $("form[name='item-form-advice']")
		if(_form.find("input[name=m_evidence]").val() ==''){
			alert('判定依据不能为空');
			return false;
		}
		$.ajax({
	        url: "index.php",
	        type: "post",
	        dataType: "json",
	        data: _form.serialize(),
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
	});
	$("#tj_final_advice").click(function(){
		if(window.confirm('确定判定并执行判定结果吗？')){
			var _form = $("form[name='item-form-final-advice']")
			if(_form.find("textarea[name=breaker_content]").val() ==''){
				alert('判定依据不能为空');
				return false;
			}
			$.ajax({
		        url: "index.php",
		        type: "post",
		        dataType: "json",
		        data: _form.serialize(),
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
	$("input[name=add_option]").click(function(){
		var _item = $(this);
		if(_item.attr('id') =='dispute_add'){
			var _option_name = 'dispute_needtodo';
			var _option_name_object = 'defend_needtodo';
			var _before_id = 'dispute_add_for';
			var _before_id_object = 'defend_add_for';
			var _ob_str = $('#dispute_add_project').val();
			var _ob_money = $('#dispute_add_money').val();
			$('#dispute_add_project').val('');
			$('#dispute_add_money').val('');
		}else{
			var _option_name = 'defend_needtodo';
			var _option_name_object = 'dispute_needtodo';
			var _before_id = 'defend_add_for';
			var _before_id_object = 'dispute_add_for';
			var _ob_str = $('#defend_add_project').val();
			var _ob_money = $('#defend_add_money').val();
			
		}
		if(_ob_str=='' || _ob_money==''){
			alert('追加项目和金额不能为空');
			return false;
		}
		$('#dispute_add_project').val('');
		$('#dispute_add_money').val('');
		$('#defend_add_project').val('');
		$('#defend_add_money').val('');
		
		if(parseFloat(_ob_money)>0){
			_ob_money_object = "-"+parseFloat(_ob_money);
		}else{
			_ob_money_object = Math.abs(parseFloat(_ob_money));
		}
		var _addStr = "<input type='checkbox' name='"+_option_name+"["+encodeURIComponent(_ob_str)+"]' value='"+_ob_money+"'>"+_ob_str+":"+_ob_money+"<br>";
		var _addStrObject = "<input type='checkbox' name='"+_option_name_object+"["+encodeURIComponent(_ob_str)+"]' value='"+_ob_money_object+"'>"+_ob_str+":"+_ob_money_object+"<br>";
		
		$('#'+_before_id).before(_addStr);	
		$('#'+_before_id_object).before(_addStrObject);	
		
	})
    
});
function del_mediate_evidence(mediate_id,key_id,mediate_assistant){
	if(window.confirm('确定要取消此证据上传吗？')){
		$.ajax({
	        url: "index.php",
	        type: "post",
	        dataType: "json",
	        data: {act:'zhengyi',op:'ajax_sub',type:'8',mediate_id:mediate_id,key:key_id,mediate_assistant:mediate_assistant},
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
function sub_form(form,type){
	var _form = $('form[name='+form+']');
	if(type==1){
		var check_num = _form.find("input[type='checkbox']:checked").length;
		if(check_num==0){
			alert('请选择补充内容');
			return false;
		}
		if(_form.find("input[type='checkbox'][value='dispute_other_wenti']").attr('checked')){
			if(_form.find("input[name='dispute_other_wenti']").val() ==''){
				alert('追加补充证据不能为空');
				return false;
			}
		}
	}else if(type==2){
		var _day = _form.find('input[name=day]').val();
		var _hour = _form.find('input[name=hour]').val();
		if(_day == '' && _hour == ''){
			alert('时限不能都为空，请重新填写');
			return false
		}
	}
	$.ajax({
        url: "index.php",
        type: "post",
        dataType: "json",
        data: _form.serialize(),
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
</script>