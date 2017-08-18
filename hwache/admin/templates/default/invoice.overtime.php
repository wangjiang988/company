<?php defined('InHG') or exit('Access Invalid!');?>

<div class="page">
  <table class="table tb-type2 order">
    <tbody>
      <tr class="space">
        <th colspan="15">订单号：&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $output['order']['order_num'];?></th>
      </tr>
      <tr>
        <td colspan="2"><ul>
            <li>
            <strong>&nbsp;&nbsp;客户姓名:</strong>
   			<?php echo $output['memberInfo']['member_truename'];?>
            </li>
            <li><strong>&nbsp;&nbsp;&nbsp;&nbsp;客户电话：</strong>
            <?php echo $output['memberInfo']['member_mobile'];?>
            </li>
            <li>
            <strong>可开票金额:</strong>
            <span><?php echo $lang['currency'].$output['order']['inv_money'];?> </span>
            </li>
            <li><strong>结算完成时间：</strong>
            	<?php echo $output['order']['updated_at'];?>
            </li>
            <li>
            <strong>&nbsp;&nbsp;&nbsp;开票失效时间:</strong>
            <span> <?php echo date("Y-m-d",strtotime($output['order']['updated_at'])+86400*90);?></span>
            </li>
            
          </ul></td>
      </tr>
      <tr>
        <th colspan="2">
        <?php if( $output['order']['overtime_invoice_status'] == 1){echo '已恢复可开票状态';}else{?>
        <a href="javascript:agreeInvoice('<?php echo $output['order']['order_num']; ?>')" class="btn"><span>同意恢复可开票</span> </a>
   		<?php }?>     
        
        </th>
      </tr>


  </table>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript">
function agreeInvoice(order_num){
	if(window.confirm('您确认需要恢复可开票状态吗')){
		$.ajax({
	        url: "index.php",
	        type: "post",
	        dataType: "json",
	        data: {
	        	act:'invoice',
				op:'agree_invoice',
				order_num:order_num
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
</script>

