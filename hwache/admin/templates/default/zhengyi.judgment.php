<?php defined('InHG') or exit('Access Invalid!');
if($output['dispute']['mediate_team_ids']!=''){
	$tmpuser = explode(",",$output['dispute']['mediate_team_ids']);
	foreach($tmpuser as $k=>$v){
		$choose_user[$v['admin_id']]=$v['admin_id'];
	}
}else{
	$choose_user = array();
}
if($output['dispute']['buy_id']==$output['dispute']['member_id']){
	$dispute_memberStr = '买方';
	$defend_memberStr = '售方';
}else{
	$dispute_memberStr = '售方';
	$defend_memberStr = '买方';
}
?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>争议管理</h3>
      <ul class="tab-base">
        
        <li><a href="index.php?act=zhengyi&op=index"><span><?php echo '所有记录';?></span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo '修改';?></span></a></li>
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
        <tr class="space">
          <th colspan=2>裁判团成员选择(温馨提示裁判团成员只能选择单数，如1,3,5,7...等)</th>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform" colspan=2>
          <form action='' method="post" name='item-form-judgment'>
          <table style="width:600px;">
          <tr><td>选择</td><td>部门</td><td>用户名</td><td>姓名</td></tr>
          <?php foreach($output['users'] as $k=>$v){?>
          <tr>
          	<td><input type="checkbox" name='user[]' value='<?php echo $v['admin_id'];?>' <?php if(isset($choose_user[$v['admin_id']])){echo 'checked';}?>></td>
          	<td><?php echo $v['gname'];?></td>
          	<td><?php echo $v['admin_name'];?></td>
          	<td><?php echo $v['admin_truename'];?></td>
          </tr>
          <?php }?>
          <tr class="noborder">
          	<td class='center' colspan=4>
          		<a href="JavaScript:void(0);" class="btn" id="submitBtn"><span>提交</span></a>
          		<a href="?act=zhengyi&op=edit&dispute_id=<?php echo $output['dispute']['id']; ?>" class="btn" ><span>返回争议继续处理</span></a>
          	</td>
          </tr>
          <input name='act' value='zhengyi' type='hidden'>
	        <input name='op' value='ajax_sub' type='hidden'>
	        <input type="hidden" name="type" value="5" />
	        <input type="hidden" name="mediate_id" value="<?php echo $output['dispute']['itemid']; ?>">
	    	<input type="hidden" name="dispute_id" value="<?php echo $output['dispute']['id']; ?>">
	    	<input type="hidden" name="defend_id" value="<?php echo $output['dispute']['defend_id']; ?>">
          </table>
          </form>
          </td>
        </tr>
        
      </tfoot>
    </table>
</div>
<script type="text/javascript">
$(function(){
	$("#submitBtn").click(function(){
		var _form = $("form[name='item-form-judgment']")
		var _user_num = _form.find("input[name='user[]']:checked").length;
		if((_user_num%2)!=1){
			alert('裁判团个数必须为单数，否则无法统计');
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
    
});

</script>