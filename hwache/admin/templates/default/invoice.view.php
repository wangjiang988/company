<?php defined('InHG') or exit('Access Invalid!');?>

<div class="page">
  <table class="table tb-type2 order">
    <tbody>
      <tr class="space">
        <th colspan="15">发票详情</th>
      </tr>
      <tr>
        <td colspan="2"><ul>
            <li>
            <strong>&nbsp;&nbsp;发票编号:</strong><?php echo $output['invoiceInfo']['inv_id'];?>
   
            </li>
            <li> <strong>&nbsp;</strong></li>
            <li><strong>&nbsp;&nbsp;&nbsp;&nbsp;订单号：</strong><?php echo $output['invoiceInfo']['order_num'];?></li>
            <li>
            <strong>可开票金额:</strong>
            <span><?php echo $lang['currency'].$output['invoiceInfo']['inv_money'];?> </span>
            </li>
            <li><strong>客户姓名：</strong><?php echo $output['memberInfo']['member_truename'];?></li>
            <li>
            <strong>&nbsp;&nbsp;&nbsp;客户电话:</strong>
            <span><?php echo $output['memberInfo']['member_mobile'];?> </span>
            </li>
            
          </ul></td>
      </tr>
      <tr class="space">
        <th colspan="2">申请开票</th>
      </tr>
      <tr>
        <td><ul>
            <li><strong>申请开票金额<?php echo $lang['nc_colon'];?></strong><?php echo $output['invoiceInfo']['inv_money'];?></li>
            <li><strong>申请开票时间<?php echo $lang['nc_colon'];?></strong><?php echo $output['invoiceInfo']['inv_apply_date'];?></li>
            <li><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;发票类型<?php echo $lang['nc_colon'];?></strong><?php echo ($output['invoiceInfo']['inv_state']==1)?'增值税普通发票':'增值税专用发票';?></li>
            <li><strong>申请开票抬头<?php echo $lang['nc_colon'];?></strong><?php echo $output['invoiceInfo']['inv_title'];?></li>
            
            <li><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;邮寄地址<?php echo $lang['nc_colon'];?></strong><?php echo $output['invoiceInfo']['inv_goto_addr'];?></li>
            <li><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;收件人<?php echo $lang['nc_colon'];?></strong><?php echo $output['invoiceInfo']['inv_rec_name'];?></li>
            <li><strong>&nbsp;&nbsp;收件人电话<?php echo $lang['nc_colon'];?></strong><?php echo $output['invoiceInfo']['inv_rec_mobphone'];?></li>
            <li>
            <?php if($output['invoiceInfo']['invoice_status']<=2){?>
            	<?php if($output['invoiceInfo']['invoice_re_edit']==0){?>
            		<a href="JavaScript:void(0);" class="btn" ><span>已退回</span></a>       
            	<?php }else{?>
     				<a href="JavaScript:void(0);" class="btn" id="invoice_re_edit"><span>信息有误退回重填</span></a>       
            	<?php }?>
            <?php }?>
            </li>
          </ul></td>
      </tr>
      <?php if($output['invoiceInfo']['inv_state']==2){?>
      <tr class="space">
        <th>补充开票信息</th>
      </tr>
      <tr>
      	<td>
      		<ul>
            <li>
            	<strong>纳税人识别号<?php echo $lang['nc_colon'];?></strong>
            	<span>
            		<?php echo $output['invoiceInfo']['inv_code'];?>
            	</span>
            </li>
            <li>
            	<strong>&nbsp;&nbsp;&nbsp;地址<?php echo $lang['nc_colon'];?></strong>
            	<span>
            		<?php echo $output['invoiceInfo']['inv_reg_addr'];?>
            	</span>
            </li>
            <li>
            	<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;电话<?php echo $lang['nc_colon'];?></strong>
            	<span>
            		<?php echo $output['invoiceInfo']['inv_reg_phone'];?>
            	</span>
            </li>
            <li>
            	<strong>开户行<?php echo $lang['nc_colon'];?></strong>
            	<span>
            	<?php echo $output['invoiceInfo']['inv_reg_bname'];?>
            	</span>
            </li>
            <li>
            	<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;账号<?php echo $lang['nc_colon'];?></strong>
            	<span>
            	<?php echo $output['invoiceInfo']['inv_reg_baccount'];?>
            	</span>
            </li>
           </ul>
      	</td>
      	</tr>
      	<?php }?>
      <tr class="space">
        <th>实际开票</th>
      </tr>
      <tr>
      	<td>
      	<form act="" name="save_invoice" method="post">
      		<table style="width:100%">
      			   <tr>
        <td><ul>
            <li>
            	<strong>&nbsp;&nbsp;&nbsp;已开发票类型<?php echo $lang['nc_colon'];?></strong>
            	<span>
            		<input type="radio" name='inv_state' value="1" <?php if($output['invoiceInfo']['inv_state']==1){echo 'checked';}?> tag='a'>增值税普通发票 &nbsp;&nbsp;
            		<input type="radio" name='inv_state' value="2" <?php if($output['invoiceInfo']['inv_state']==2){echo 'checked';}?> tag='a'>增值税专用发票
            	</span>
            </li>
            <li>
            	<strong>已开发票抬头<?php echo $lang['nc_colon'];?></strong>
            	<span><input type="text" name='inv_title' value="<?php echo $output['invoiceInfo']['inv_title'];?>" tag='a'></span>
            </li>
            <li>
            	<strong>&nbsp;&nbsp;&nbsp;已开发票金额<?php echo $lang['nc_colon'];?></strong>
            	<span><input type="text" name='inv_money' value="<?php echo $output['invoiceInfo']['inv_money'];?>" tag='a'></span>
            </li>
            <li>
            	<strong>已开发票编号<?php echo $lang['nc_colon'];?></strong>
            	<span><input type="text" name='inv_number' value="<?php echo $output['invoiceInfo']['inv_number'];?>" tag='a'></span>
            </li>
             <?php if( $output['invoiceInfo']['invoice_status'] ==1){?>
			<li>
				<strong>空白发票可用数<?php echo $lang['nc_colon'];?></strong>
				<span><?php echo $output['invoiceNum'];?> 份</span>
			</li>
			<?php }?>
			<li>&nbsp;</li>
			
          </ul>
        </td>
       </tr>
       
      	<!-- 开票提交按钮 -->
      <?php if( $output['invoiceInfo']['invoice_status'] ==1){?>
       <tr >
        <td colspan="15" align="center">
        <a href="JavaScript:void(0);" class="btn" id="save_invoice_1"><span>提交开票内容</span></a>
         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="JavaScript:void(0);" class="btn"><span>返回</span></a>
        </td>
      </tr>
      <?php }?>
      
    <!-- 发票已开显示 -->
      <?php if( $output['invoiceInfo']['invoice_status'] >=2){?>
   
      <tr >
        <td>
        	<ul>
            <li><strong>开票人<?php echo $lang['nc_colon'];?></strong><?php echo $output['invoiceInfo']['inv_operator'];?></li>
            <li><strong>提交开票内容时间<?php echo $lang['nc_colon'];?></strong><?php echo $output['invoiceInfo']['inv_operator_date'];?></li>
            </ul>
        </td>
        
     </tr>
     
      <tr class="space">
        <th>寄送发票</th>
      </tr>

      <tr>
        <td>
        <ul>
            <li>
            	<strong>寄送时间<?php echo $lang['nc_colon'];?></strong>
            	<?php 
            	if($output['invoiceInfo']['inv_deliver_date']=='' || $output['invoiceInfo']['inv_deliver_date']=='0000-00-00'){
            		$output['invoiceInfo']['inv_deliver_date'] = date("Y-m-d");
            	}
            	?>
            	<input class="txt date" type="text" value="<?php echo $output['invoiceInfo']['inv_deliver_date'];?>"  name="inv_deliver_date" id="inv_deliver_date" tag='b'>
            </li>
            <li>
            	<strong>快递名称<?php echo $lang['nc_colon'];?></strong>
            	<span>
            		<select name="inv_deliver" id="inv_deliver" tag='b'>
            			<?php foreach($output['express'] as $k=>$v){?>
            			<option value="<?php echo $v['e_name'];?>" <?php if($v['e_name'] == $output['invoiceInfo']['inv_deliver']){echo 'selected';}?>><?php echo $v['e_name'];?></option>
            			<?php }?>
            		</select>
            	</span>
            </li>
            <li>&nbsp;</li>
			<li>
				<strong>&nbsp;&nbsp;运单号<?php echo $lang['nc_colon'];?></strong>
				<input  type="text" value="<?php echo $output['invoiceInfo']['inv_deliver_number'];?>"  name="inv_deliver_number" tag='b'>
			<li>
				<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;备注<?php echo $lang['nc_colon'];?></strong>
				<input  type="text" value="<?php echo $output['invoiceInfo']['inv_note'];?>"  name="inv_note" tag='b'>
			</li>
			
          </ul></td>
      </tr>
       <!-- 发票已开显示end -->
       
       <!-- 发票申请重新信息 start -->
      <?php if($output['invoiceInfo']['invoice_status'] == 5){?>
	<tr class="space">
        <th>重开申请</th>
    </tr>
    <tr>
        <td>
        <ul>
            <li>
            <strong>重开发票同意人:</strong><?php echo $output['invoiceInfo']['re_do_operator'];?>
            </li>
            <li> <strong>重开发票同意时间:</strong><?php echo $output['invoiceInfo']['re_do_date'];?></li>
            <li><strong>重开发票原因:</strong><?php echo $output['invoiceReInfo']['return_reason'];?></li>
            <li>
            <strong>原发票寄回快递:</strong>
            <span><?php echo$output['invoiceReInfo']['return_deliver'];?> </span>
            </li>
            <li><strong>原发票寄回快递运单号：</strong><?php echo $output['invoiceReInfo']['return_deliver_num'];?></li>
            <li>
            <strong>申请重新开票金额:</strong>
            <span><?php echo $output['invoiceReInfo']['inv_money'];?> </span>
            </li>
            <li>
            <strong>申请重新开票抬头:</strong>
            <span><?php echo $output['invoiceReInfo']['inv_title'];?> </span>
            </li>
            <li>
            <strong>申请重新开票类型:</strong>
            <span><?php echo ($output['invoiceReInfo']['inv_state']==1)?'增值税普通发票':'增值税专用发票';?> </span>
            </li>
            <li>
            <strong>申请重新开票邮寄地址:</strong>
            <span><?php echo $output['invoiceReInfo']['inv_goto_addr'];?> </span>
            </li>
            <li>
            <strong>申请重新开票收件人:</strong>
            <span><?php echo $output['invoiceReInfo']['inv_rec_name'];?> </span>
            </li>
            <li>
            <strong>申请重新开票收件人电话:</strong>
            <span><?php echo $output['invoiceReInfo']['inv_rec_mobphone'];?> </span>
            </li>
           
          </ul>
        
        </td>
    </tr>    
    <?php }?>
    <!-- 发票重开申请信息end -->
    
    <!-- 重开发票 增值税start -->
    <?php if(isset($output['invoiceReInfo']) && $output['invoiceReInfo']['inv_state']==2){?>
      <tr class="space">
        <th>重开补充信息</th>
      </tr>
      <tr>
      	<td>
      		<ul>
            <li>
            	<strong>纳税人识别号<?php echo $lang['nc_colon'];?></strong>
            	<span>
            		<?php echo $output['invoiceReInfo']['inv_code'];?>
            	</span>
            </li>
            <li>
            	<strong>&nbsp;&nbsp;&nbsp;地址<?php echo $lang['nc_colon'];?></strong>
            	<span>
            		<?php echo $output['invoiceReInfo']['inv_reg_addr'];?>
            	</span>
            </li>
            <li>
            	<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;电话<?php echo $lang['nc_colon'];?></strong>
            	<span>
            		<?php echo $output['invoiceReInfo']['inv_reg_phone'];?>
            	</span>
            </li>
            <li>
            	<strong>开户行<?php echo $lang['nc_colon'];?></strong>
            	<span>
            	<?php echo $output['invoiceReInfo']['inv_reg_bname'];?>
            	</span>
            </li>
            <li>
            	<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;账号<?php echo $lang['nc_colon'];?></strong>
            	<span>
            	<?php echo $output['invoiceReInfo']['inv_reg_baccount'];?>
            	</span>
            </li>
           </ul>
      	</td>
      	</tr>
      	<?php }?>
      	<!-- 重开发票 增值税end -->
    </tbody>
    
    <tfoot>
      <tr class="tfoot">
        <td>
        <?php if($output['invoiceInfo']['invoice_status'] !=5){?>
        <a href="JavaScript:void(0);" class="btn" id="save_invoice_2" style="display:none"><span>提交</span></a>
        <!--  <a href="JavaScript:void(0);" class="btn" ><span>返回</span></a>-->
        <a href="JavaScript:void(0);" class="btn" id="modify_invoice"><span>修改开票内容</span></a>
        	<?php if($output['invoiceInfo']['re_do_status']==0){?>
        		<a href="JavaScript:void(0);" class="btn" id="agree_redo_invoice" data-value='1'><span>同意重开票</span></a>
        	<?php }else{?>
        		<a href="JavaScript:void(0);" class="btn" id="agree_redo_invoice" data-value='0'><span>取消重开票</span></a>
        	<?php }?>
        <?php }else{?>
        	<?php if($output['invoiceReInfo']['return_rec_name']==''){?>
        <a href="JavaScript:void(0);" class="btn" id="save_rec_invoice"><span>确认收到原发票</span></a>
        <!--<a href="JavaScript:void(0);" class="btn" ><span>返回</span></a>-->
        <a href="JavaScript:void(0);" class="btn" id="save_re_edit_invoice"><span>信息有误退回充填</span></a>
        	<?php }else{?>
        	<a href="JavaScript:void(0);" class="btn"><span>原发票已经收到</span></a>
        	<?php }?>
        <?php }?>
        </td>
      </tr>
      <?php 
      if(!empty($output['invoiceInfo']['re_do_date'])){
      		if($output['invoiceInfo']['re_do_status']==1){
      ?>
      		<tr><td>重开票同意人：<?php echo $output['invoiceInfo']['re_do_operator'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;重开票同意时间：<?php echo $output['invoiceInfo']['re_do_date'];?></td></tr>
      <?php }else{?>
      		<tr><td>取消同意人：<?php echo $output['invoiceInfo']['re_do_operator'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;取消同意时间：<?php echo $output['invoiceInfo']['re_do_date'];?></td></tr>
      <?php 
      		}
      }
      ?>
    </tfoot>
    
   </table>
   <?php }?>
   <?php if($output['invoiceInfo']['invoice_status'] >=1){?>
   			<?php 
   			if($output['invoiceInfo']['invoice_status']==1){
   				$todo = "save_invoice_1";
   			}else{
   				$todo = "save_invoice_2";
   			}
   			?>
   			<input type="hidden" name='todo' value="<?php echo $todo;?>">
   			<input type="hidden" name='order_num' value="<?php echo $output['invoiceInfo']['order_num'];?>">
      		<input type="hidden" name='inv_id' value="<?php echo $output['invoiceInfo']['inv_id'];?>">
            <input type="hidden" name='act' value="invoice">
            <input type="hidden" name='op' value="show">
   </form>
   
      </td>
      </tr>
      
    	
    <?php }?>
    
    
    <!-- 重开发票Start -->
    <?php if($output['invoiceInfo']['invoice_status'] == 5 && $output['invoiceReInfo']['return_rec_name']!=''){?>
	<tr class="space">
        <th>实际重开</th>
    </tr>
    <tr>
    <td>
	    <form act="" name="save_re_invoice" method="post">
	      	<table style="width:100%">
	      	<tr>
	        <td><ul>
	            <li>
	            	<strong>&nbsp;&nbsp;&nbsp;已开发票类型<?php echo $lang['nc_colon'];?></strong>
	            	<span>
	            		<input type="radio" name='inv_state' value="1" <?php if($output['invoiceReInfo']['inv_state']==1){echo 'checked';}?> tag='aa'>增值税普通发票 &nbsp;&nbsp;
	            		<input type="radio" name='inv_state' value="2" <?php if($output['invoiceReInfo']['inv_state']==2){echo 'checked';}?> tag='aa'>增值税专用发票
	            	</span>
	            </li>
	            <li>
	            	<strong>已开发票抬头<?php echo $lang['nc_colon'];?></strong>
	            	<span><input type="text" name='inv_title' value="<?php echo $output['invoiceReInfo']['inv_title'];?>" tag='aa'></span>
	            </li>
	            <li>
	            	<strong>&nbsp;&nbsp;&nbsp;已开发票金额<?php echo $lang['nc_colon'];?></strong>
	            	<span><input type="text" name='inv_money' value="<?php echo $output['invoiceReInfo']['inv_money'];?>" tag='aa'></span>
	            </li>
	            <li>
	            	<strong>已开发票编号<?php echo $lang['nc_colon'];?></strong>
	            	<span><input type="text" name='inv_number' value="<?php echo $output['invoiceReInfo']['inv_number'];?>" tag='aa'></span>
	            </li>
	             <?php if( $output['invoiceReInfo']['invoice_status'] ==1){?>
				<li>
					<strong>空白发票可用数<?php echo $lang['nc_colon'];?></strong>
					<span><?php echo $output['invoiceNum'];?> 份</span>
				</li>
				<?php }?>
				<li>&nbsp;</li>
				
	          </ul>
	        </td>
	       </tr>
	     
			<!-- //重开发票按钮 -->
		 <?php if( $output['invoiceReInfo']['invoice_status'] ==1){?>
	      	<tr >
	        <td colspan="15" align="center">
	        <a href="JavaScript:void(0);" class="btn" id="save_re_invoice_1"><span>提交开票内容</span></a>
	         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	        <a href="JavaScript:void(0);" class="btn"><span>返回</span></a>
	        </td>
	      </tr>
	      <?php }?>
    <?php if( $output['invoiceReInfo']['invoice_status'] >=2){?>
	      <tr >
	        <td>
	        	<ul>
	            <li><strong>开票人<?php echo $lang['nc_colon'];?></strong><?php echo $output['invoiceReInfo']['inv_operator'];?></li>
	            <li><strong>提交开票内容时间<?php echo $lang['nc_colon'];?></strong><?php echo $output['invoiceReInfo']['inv_operator_date'];?></li>
	            </ul>
	        </td>
	        
	     </tr>
	     <tr class="space">
        <th>重寄发票</th>
      </tr>

      <tr>
        <td>
        <ul>
            <li>
            	<strong>寄送时间<?php echo $lang['nc_colon'];?></strong>
            	<?php 
            	if($output['invoiceReInfo']['inv_deliver_date']=='' || $output['invoiceReInfo']['inv_deliver_date']=='0000-00-00'){
            		$output['invoiceReInfo']['inv_deliver_date'] = date("Y-m-d");
            	}
            	?>
            	<input class="txt date" type="text" value="<?php echo $output['invoiceReInfo']['inv_deliver_date'];?>"  name="inv_deliver_date" id="inv_re_deliver_date" tag='bb'>
            </li>
            <li>
            	<strong>快递名称<?php echo $lang['nc_colon'];?></strong>
            	<span>
            		<select name="inv_deliver" id="inv_deliver" tag='bb'>
            			<?php foreach($output['express'] as $k=>$v){?>
            			<option value="<?php echo $v['e_name'];?>" <?php if($v['e_name'] == $output['invoiceReInfo']['inv_deliver']){echo 'selected';}?>><?php echo $v['e_name'];?></option>
            			<?php }?>
            		</select>
            	</span>
            </li>
            <li>&nbsp;</li>
			<li>
				<strong>&nbsp;&nbsp;运单号<?php echo $lang['nc_colon'];?></strong>
				<input  type="text" value="<?php echo $output['invoiceReInfo']['inv_deliver_number'];?>"  name="inv_deliver_number" tag='bb'>
			<li>
				<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;备注<?php echo $lang['nc_colon'];?></strong>
				<input  type="text" value="<?php echo $output['invoiceReInfo']['inv_note'];?>"  name="inv_note" tag='bb'>
			</li>
			
          </ul></td>
      </tr>
     <tfoot>
      <tr class="tfoot">
        <td>
        <?php if($output['invoiceReInfo']['invoice_status'] !=5){?>
        <a href="JavaScript:void(0);" class="btn" id="save_re_invoice_2"><span>提交</span></a>
        <!-- <a href="JavaScript:void(0);" class="btn" ><span>返回</span></a> -->
        <a href="JavaScript:void(0);" class="btn" id="modify_re_invoice"><span>修改开票内容</span></a>
        <?php }else{?>
        	<?php if($output['invoiceReInfo']['return_rec_name']==''){?>
        <a href="JavaScript:void(0);" class="btn" id="save_re_rec_invoice"><span>确认收到原发票</span></a>
        <!-- <a href="JavaScript:void(0);" class="btn" ><span>返回</span></a> -->
        <a href="JavaScript:void(0);" class="btn" id="save_re_re_edit_invoice"><span>信息有误退回充填</span></a>
        	<?php }else{?>
        	<a href="JavaScript:void(0);" class="btn"><span>原发票已经收到</span></a>
        	<?php }?>
        <?php }?>
        </td>
      </tr>
    </tfoot>
    
      <?php }?>
	     </table>
	     	<?php 
   			if($output['invoiceReInfo']['invoice_status']==1){
   				$todo = "save_re_invoice_1";
   			}else{
   				$todo = "save_re_invoice_2";
   			}
   			?>
	     	<input type="hidden" name='todo' value="<?php echo $todo;?>">
   			<input type="hidden" name='order_num' value="<?php echo $output['invoiceReInfo']['order_num'];?>">
      		<input type="hidden" name='inv_id' value="<?php echo $output['invoiceReInfo']['inv_id'];?>">
      		<input type="hidden" name='ori_inv_id' value="<?php echo $output['invoiceInfo']['inv_id'];?>">
            <input type="hidden" name='act' value="invoice">
            <input type="hidden" name='op' value="show">
	     </form>
    
    </td>
    </tr>
    
    <?php }?>
    <!-- 重开发票END -->
  </table>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript">
$(function(){
	var _invoice_status = '<?php echo $output['invoiceInfo']['invoice_status']; ?>';
	var _invoice_re_status = '<?php echo $output['invoiceReInfo']['invoice_status']; ?>';
	if(_invoice_status ==2){
		$("form[name=save_invoice]").find('input[tag=a]').attr("disabled",true);
	}else if(_invoice_status >=3){
		$("form[name=save_invoice]").find('input[tag=a]').attr("disabled",true);
		$("form[name=save_invoice]").find('input[tag=b]').attr("disabled",true);
		$("form[name=save_invoice]").find('select[name=inv_deliver]').attr("disabled",true);
		
	}
	if(_invoice_re_status ==2){
		$("form[name=save_re_invoice]").find('input[tag=aa]').attr("disabled",true);
	}else if(_invoice_re_status >=3){
		$("form[name=save_re_invoice]").find('input[tag=aa]').attr("disabled",true);
		$("form[name=save_re_invoice]").find('input[tag=bb]').attr("disabled",true);
		$("form[name=save_re_invoice]").find('select[name=inv_deliver]').attr("disabled",true);
		
	}
	
	$('#inv_deliver_date').datepicker({dateFormat: 'yy-mm-dd'});
	$('#inv_re_deliver_date').datepicker({dateFormat: 'yy-mm-dd'});
    $('#save_invoice_1').click(function(){
        var _inv_num = '<?php echo $output['invoiceNum'];?>';
    	var _inv_title = $("input[name='inv_title']").val();
    	var _inv_money = $("input[name='inv_money']").val();
    	var _inv_number = $("input[name='inv_number']").val();
    	if(parseInt(_inv_num) == 0){
        	alert('目前没有对应的发票存在');
        	return false;
    	}
    	if(_inv_title == ''){
        	alert('发票抬头不能为空');
        	return false;
    	}
    	if(_inv_money == ''){
        	alert('开票金额不能为空');
        	return false;
    	}
    	if(_inv_number == ''){
        	alert('发票编号不能为空');
        	return false;
    	}
    	$("form[name=save_invoice]").submit();
    });

    $('#save_invoice_2').click(function(){
       
        if(_invoice_status >=3 && $("input[name='todo']").val() !='save_invoice'){
            alert('数据已经提交过，请点击修改按钮后再提交');
            return false
        }
    	var _inv_deliver_date = $("input[name='inv_deliver_date']").val();
    	var _inv_deliver = $("select[name='inv_deliver']").val();
    	var _inv_deliver_number = $("input[name='inv_deliver_number']").val();

    	if(_inv_deliver_date == ''){
        	alert('投递日期不能为空');
        	return false;
    	}
    	if(_inv_deliver == ''){
        	alert('请选择快递公司');
        	return false;
    	}
    	if(_inv_deliver_number == ''){
        	alert('快递单号不能为空');
        	return false;
    	}
    	$("form[name=save_invoice]").submit();
    });
    $("#modify_invoice").click(function(){
        $(this).hide();
    	$("#save_invoice_2").css('display','');
    	
    	$('input:disabled').attr("disabled",false);
    	$('select[name=inv_deliver]').attr("disabled",false);
    	$("input[name='todo']").val('save_invoice');
    	
     });

    $("#agree_redo_invoice").click(function(){
    	_re_do_status = $(this).attr('data-value');
    	$.ajax({
            url: "index.php",
            type: "post",
            dataType: "json",
            data: {
            	act:'invoice',
				op:'ajax',
				type:'redo_invoice',
				inv_id:'<?php echo $output['invoiceInfo']['inv_id']; ?>',
				order_num:'<?php echo $output['invoiceInfo']['order_num']; ?>',
				re_do_status:_re_do_status
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
    });
    $("#modify_re_invoice").click(function(){
    	var _form = $("form[name=save_re_invoice]");
    	_form.find('input:disabled').attr("disabled",false);
    	_form.find('select[name=inv_deliver]').attr("disabled",false);
    	_form.find("input[name='todo']").val('save_re_invoice');
    	
     });
    $("#invoice_re_edit").click(function(){
    	$.ajax({
            url: "index.php",
            type: "post",
            dataType: "json",
            data: {
            	act:'invoice',
				op:'ajax',
				type:'invoice_re_edit',
				inv_id:'<?php echo $output['invoiceInfo']['inv_id']; ?>',
				order_num:'<?php echo $output['invoiceInfo']['order_num']; ?>'
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
    //申请重开 发票退回重填
    $("#save_re_edit_invoice").click(function(){
    	$.ajax({
            url: "index.php",
            type: "post",
            dataType: "json",
            data: {
            	act:'invoice',
				op:'ajax',
				type:'invoice_re_edit',
				inv_id:'<?php echo $output['invoiceReInfo']['inv_id']; ?>',
				order_num:'<?php echo $output['invoiceReInfo']['order_num']; ?>'
            }
            ,
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
    //退回发票已经收到
    $("#save_rec_invoice").click(function(){
    	$.ajax({
            url: "index.php",
            type: "post",
            dataType: "json",
            data: {
            	act:'invoice',
				op:'ajax',
				type:'invoice_rec',
				inv_id:'<?php echo $output['invoiceReInfo']['inv_id']; ?>',
				order_num:'<?php echo $output['invoiceReInfo']['order_num']; ?>'
            }
            ,
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

    $("#save_re_invoice_1").click(function(){
		var _form = $("form[name=save_re_invoice]");
		var _inv_num = '<?php echo $output['invoiceNum'];?>';
    	var _inv_title = _form.find("input[name='inv_title']").val();
    	var _inv_money = _form.find("input[name='inv_money']").val();
    	var _inv_number = _form.find("input[name='inv_number']").val();
    	if(parseInt(_inv_num) == 0){
        	alert('目前没有对应的发票存在');
        	return false;
    	}
    	if(_inv_title == ''){
        	alert('发票抬头不能为空');
        	return false;
    	}
    	if(_inv_money == ''){
        	alert('开票金额不能为空');
        	return false;
    	}
    	if(_inv_number == ''){
        	alert('发票编号不能为空');
        	return false;
    	}
    	$("form[name=save_re_invoice]").submit();
      })

      $('#save_re_invoice_2').click(function(){
    	  var _form = $("form[name=save_re_invoice]");
          if(_invoice_re_status >=3 && _form.find("input[name='todo']").val() !='save_re_invoice'){
              alert('数据已经提交过，请点击修改按钮后再提交');
              return false
          }
      	var _inv_deliver_date = _form.find("input[name='inv_deliver_date']").val();
      	var _inv_deliver = _form.find("select[name='inv_deliver']").val();
      	var _inv_deliver_number = _form.find("input[name='inv_deliver_number']").val();

      	if(_inv_deliver_date == ''){
          	alert('投递日期不能为空');
          	return false;
      	}
      	if(_inv_deliver == ''){
          	alert('请选择快递公司');
          	return false;
      	}
      	if(_inv_deliver_number == ''){
          	alert('快递单号不能为空');
          	return false;
      	}
      	$("form[name=save_re_invoice]").submit();
      });

    
    $("#save_re_edit_invoice").click(function(){
    	$.ajax({
            url: "index.php",
            type: "post",
            dataType: "json",
            data: {
            	act:'invoice',
				op:'ajax',
				type:'invoice_re_edit',
				inv_id:'<?php echo $output['invoiceReInfo']['inv_id']; ?>',
				order_num:'<?php echo $output['invoiceReInfo']['order_num']; ?>'
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
    
})
</script>

