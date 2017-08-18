<?php
$get = $output['get'];
?>
<li><a href="<?=url('hc_vouchers','index');?>" <?=showHorve($get,'index')?>><span>管理</span></a><em> | </em></li>
<li><a href="<?=url('hc_vouchers','group');?>" <?=showHorve($get,'group')?>><span>代金券组</span></a><em> | </em></li>
<li><a href="<?=url('hc_vouchers','release',['activated'=>1]);?>" <?=showHorve($get,'release',1)?>><span>投放激活列表</span></a><em> | </em></li>
<li><a href="<?=url('hc_vouchers','release');?>" <?=showHorve($get,'release')?>><span>投放免激活列表</span></a><em> | </em></li>
<li><a href="<?=url('hc_vouchers','clever');?>" <?=showHorve($get,'clever')?>><span>智能代金券</span></a><em> | </em></li>
<li><a href="<?=url('hc_vouchers','promotion');?>" <?=showHorve($get,'promotion')?>><span>代金券推广</span></a><em> </em></li>