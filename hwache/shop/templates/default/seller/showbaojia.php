<?php defined('InHG') or exit('Access Invalid!');?>

<div class="tabmenu">
  <ul class="tab pngFix">
<li class="active"><a href="">查看报价</a></li></ul>
</div>

<table class="ncsc-table-style">
  <thead>
    <tr nc_type="table_header">
      <th colspan="6" align="left" class="w30" style="text-align:left">基本信息</th>
    </tr>
      </thead>
  <tbody>
  <tr>
  	<td align="left" style="text-align:left;">报价编号：</td>
	<td align="left" style="text-align:left"><?php echo $output['baojia']['bj_serial']; ?></td>
	<td align="left" style="text-align:left">销售商：</td>
	<td align="left" style="text-align:left"><?php echo $output['baojia']['dealer_name']; ?></td>
	<td align="left" style="text-align:left">车型：</td>
	<td align="left" style="text-align:left"><?php echo $output['baojia']['gc_name']; ?></td>
  </tr>
	<tr>
  	<td align="left" style="text-align:left;">车身颜色：</td>
	<td align="left" style="text-align:left"><?php echo $output['body_color']; ?></td>
	<td align="left" style="text-align:left">内饰颜色：</td>
	<td align="left" style="text-align:left"><?php echo $output['interior_color']; ?></td>
	<td align="left" style="text-align:left">进口/国产：</td>
	<td align="left" style="text-align:left"><?php if($output['guobie']==1){echo "进口";}else{echo "国产";} ?></td>
	</tr>
	<tr>
  	<td align="left" style="text-align:left;">座位数：</td>
	<td align="left" style="text-align:left"><?php echo $output['seat_num']; ?></td>
	<td align="left" style="text-align:left">出厂年月或交车周期：</td>
	<td align="left" style="text-align:left"><?php echo $output['baojia']['bj_producetime']; ?>/<?php echo $output['baojia']['bj_jc_period']; ?></td>
	<td align="left" style="text-align:left">裸车开票价格：</td>
	<td align="left" style="text-align:left"><?php echo $output['price']['bj_lckp_price']; ?> 元</td>
	</tr>
	<tr>
  	<td align="left" style="text-align:left;">厂商指导价：</td>
	<td align="left" style="text-align:left"><?php echo $output['zhidaojia']; ?> 元</td>
	<td align="left" style="text-align:left">我的服务费：</td>
	<td align="left" style="text-align:left"><?php echo $output['price']['bj_my_service_price']; ?> 元</td>
	<td align="left" style="text-align:left">消费者定金：</td>
	<td align="left" style="text-align:left"><?php echo $output['price']['bj_earnest_price']; ?> 元</td>
	</tr>
	<tr>
  	<td align="left" style="text-align:left;">有效时间至：</td>
	<td align="left" style="text-align:left"><?php echo date('Y-m-d',$output['baojia']['bj_end_time']); ?></td>
	<td align="left" style="text-align:left">行驶里程：</td>
	<td align="left" style="text-align:left"><?php echo $output['baojia']['bj_licheng']; ?> 公里</td>
	<td align="left" style="text-align:left"></td>
	<td align="left" style="text-align:left"></td>
	</tr>
  </tbody>
</table>
<table class="ncsc-table-style">
  <thead>
    <tr nc_type="table_header">
      <th colspan="6" align="left" class="w30" style="text-align:left">附加信息</th>
    </tr>
      </thead>
  <tbody>
  <tr>
  	<td align="left" style="text-align:left;">付款方式：</td>
	<td align="left" style="text-align:left"><?php if($output['baojia']['bj_pay_type']==1){echo "全款";}else{echo "贷款";} ?></td>
	<td align="left" style="text-align:left">可售区域：</td>
	<td align="left" style="text-align:left"><?php echo $output['area']; ?></td>
	<td align="left" style="text-align:left">商业保险限定：</td>
	<td align="left" style="text-align:left"><?php if($output['baojia']['bj_baoxian']==1){echo "是";}else{echo "否";} ?></td>
  </tr>
	<tr>
  	<td align="left" style="text-align:left;">上牌服务限定：</td>
	<td align="left" style="text-align:left"><?php if($output['baojia']['bj_shangpai']==1){echo "是";}else{echo "否";} ?></td>
	<td align="left" style="text-align:left">上牌服务费：</td>
	<td align="left" style="text-align:left"><?php echo $output['price']['bj_shangpai_price']; ?> 元</td>
	<td align="left" style="text-align:left">上临牌服务费：</td>
	<td align="left" style="text-align:left"><?php echo $output['price']['bj_linpai_price']; ?> 元</td>
	</tr>
	<tr>
  	<td align="left" style="text-align:left;">卖方其他收费：</td>
	<td align="left" style="text-align:left"><?php echo $output['price']['bj_other_price']; ?> 元</td>
	<td align="left" style="text-align:left">上牌地牌照费用：</td>
	<td align="left" style="text-align:left"><?php echo $output['price']['bj_paizhao_price']; ?> 元</td>
	<td align="left" style="text-align:left">国家节能补贴金额：</td>
	<td align="left" style="text-align:left"><?php echo $output['baojia']['bj_butie']; ?> 元</td>
	</tr>
	<tr>
  	<td align="left" style="text-align:left;">补贴办理手续条件：</td>
	<td align="left" style="text-align:left"><?php echo implode(unserialize($output['butie_type']['serialize_data'])); ?></td>
	<td align="left" style="text-align:left">交车时交付文件和其他：</td>
	<td align="left" style="text-align:left"><?php echo implode(unserialize($output['wenjian']['serialize_data'])); ?></td>
	<td align="left" style="text-align:left"></td>
	<td align="left" style="text-align:left"></td>
	</tr>
	
  </tbody>
</table>
<table class="ncsc-table-style">
  <thead>
    <tr nc_type="table_header">
      <th colspan="6" align="left" class="w30" style="text-align:left">选装件信息</th>
    </tr>
    </thead>
    <tr>
    	<th>选装件名称</th>
    	<th>选装件品牌</th>
    	<th>型号</th>
    	<th>原厂选装件折扣率</th>
    	<th>总价</th>
    </tr>
	<?php foreach ($output['xzjs'] as $key => $value) {
		
	?>
    <tr>
    	<td style="text-align:left;"><?php echo $value['xzj_title'];?></td>
    	<td style="text-align:left;"><?php echo $value['xzj_brand'];?></td>
    	<td style="text-align:left;"><?php echo $value['xzj_model'];?></td>
		<td style="text-align:left;"><?php echo $value['xzj_discount'];?></td>
    	<td style="text-align:left;"><?php echo $value['xzj_price'];?></td>
    </tr>
    <?php }?>
  <tbody>
  
  
  </tbody>
</table>
<table class="ncsc-table-style">
  <thead>
    <tr nc_type="table_header">
      <th colspan="6" align="left" class="w30" style="text-align:left">赠品或其他服务</th>
    </tr>
    </thead>
    <tr>
      <th>赠品名称</th>
      <th>价值</th>
      <th>备注</th>
      
    </tr>
  <?php foreach ($output['zengpin'] as $key => $value) {
    
  ?>
    <tr>
      <td style="text-align:left;"><?php echo $value['title'];?></td>
      <td style="text-align:left;"><?php echo $value['price'];?></td>
      <td style="text-align:left;"><?php echo $value['beizhu'];?></td>
    
    </tr>
    <?php }?>
  <tbody>
  
  
  </tbody>
</table>
<?php if ($output['baojia']['bj_baoxian']): ?>
  <table class="ncsc-table-style">
  <thead>
    <tr nc_type="table_header">
      <th colspan="6" align="left" class="w30" style="text-align:left">保费信息</th>
    </tr>
    <tr>
      <td>
        <table cellspacing="0" cellpadding="0" border="0" class="spec_table">
    <thead>
      <tr>
        <th align="left" nctype="spec_name_1">保险项目<input type="hidden" value="1" name="type"></th>
        <?php if ($output['seat_num']<6): ?>
          <th align="left" class="w200">个人非营运(6座以下)</th>
        <th align="left" class="w200">公司非营运(6座以下)</th>
          <?php else: ?>
            <th align="left" class="w200">个人非营运(6~10座)</th>
        <th align="left" class="w200">公司非营运(6~10座)</th>
        <?php endif ?>
        

        </tr>
    </thead>
    <tbody>
      <tr>
        <td align="left">机动车损失险：</td>
    <?php foreach ($output['csx'] as $key => $value) {?>
        <td align="left"><?php echo $value['price']; ?></td>
        <?php }?>
      </tr>
      <tr>
        <td align="left">机动车盗抢险：</td>
        <?php foreach ($output['daoqiang'] as $key => $value): ?>
          <td align="left"><?php echo $value['price']; ?></td>
        <?php endforeach ?>
        
        <td align="left">&nbsp;</td>
        </tr>
      
        <tr>
        <td align="left">第三者责任险5万元赔付额度：</td>
      <?php foreach ($output['sanzhe'] as $key => $value){ 
          if($value['compensate']!=5) continue;
        ?>
        <td align="left"><?php echo $value['price']; ?></td>
        <?php } ?>
        </tr>
      <tr>
        <td align="left">第三者责任险10万元赔付额度：</td>
      <?php foreach ($output['sanzhe'] as $key => $value){ 
          if($value['compensate']!=10) continue;
        ?>
        <td align="left"><?php echo $value['price']; ?></td>
        <?php } ?>
        </tr>
      <tr>
        <td align="left">第三者责任险15万元赔付额度：</td>
      <?php foreach ($output['sanzhe'] as $key => $value){ 
          if($value['compensate']!=15) continue;
        ?>
        <td align="left"><?php echo $value['price']; ?></td>
        <?php } ?>
      </tr>
      <tr>
        <td align="left">第三者责任险20万元赔付额度：</td>
      <?php foreach ($output['sanzhe'] as $key => $value){ 
          if($value['compensate']!=20) continue;
        ?>
        <td align="left"><?php echo $value['price']; ?></td>
        <?php } ?>
      </tr>
      <tr>
        <td align="left">第三者责任险30万元赔付额度：</td>
      <?php foreach ($output['sanzhe'] as $key => $value){ 
          if($value['compensate']!=30) continue;
        ?>
        <td align="left"><?php echo $value['price']; ?></td>
        <?php } ?>
      </tr>
      <tr>
        <td align="left">第三者责任险50万元赔付额度：</td>
      <?php foreach ($output['sanzhe'] as $key => $value){ 
          if($value['compensate']!=50) continue;
        ?>
        <td align="left"><?php echo $value['price']; ?></td>
        <?php } ?>
      </tr>
      <tr>
        <td align="left">第三者责任险100万元赔付额度：</td>
      <?php foreach ($output['sanzhe'] as $key => $value){ 
          if($value['compensate']!=100) continue;
        ?>
        <td align="left"><?php echo $value['price']; ?></td>
        <?php } ?>
      </tr>

      <tr>
        <td align="left">车上人员(司机1万事故责任限额)责任险：</td>
        <?php foreach ($output['renyuan'] as $key => $value){ 
          if($value['compensate']!=1 || $value['staff']!='sj') continue;
        ?>
        <td align="left"><?php echo $value['price']; ?></td>
        <?php } ?>
        </tr>
      
      <tr>
        <td align="left">车上人员(司机2万事故责任限额)责任险：</td>
        <?php foreach ($output['renyuan'] as $key => $value){ 
          if($value['compensate']!=2 || $value['staff']!='sj') continue;
        ?>
        <td align="left"><?php echo $value['price']; ?></td>
        <?php } ?>
        </tr>
      
      <tr>
        <td align="left">车上人员(司机3万事故责任限额)责任险：</td>
        <?php foreach ($output['renyuan'] as $key => $value){ 
          if($value['compensate']!=3 || $value['staff']!='sj') continue;
        ?>
        <td align="left"><?php echo $value['price']; ?></td>
        <?php } ?>
        </tr>
      
      <tr>
        <td align="left">车上人员(司机4万事故责任限额)责任险：</td>
        <?php foreach ($output['renyuan'] as $key => $value){ 
          if($value['compensate']!=4 || $value['staff']!='sj') continue;
        ?>
        <td align="left"><?php echo $value['price']; ?></td>
        <?php } ?>
        </tr>
      
      <tr>
        <td align="left">车上人员(司机5万事故责任限额)责任险：</td>
        <?php foreach ($output['renyuan'] as $key => $value){ 
          if($value['compensate']!=5 || $value['staff']!='sj') continue;
        ?>
        <td align="left"><?php echo $value['price']; ?></td>
        <?php } ?>
        </tr>
      
      
      
      <tr>
        <td align="left">车上人员(乘客单个座位1万事故责任限额)责任险：</td>
        <?php foreach ($output['renyuan'] as $key => $value){ 
          if($value['compensate']!=1 || $value['staff']!='ck') continue;
        ?>
        <td align="left"><?php echo $value['price']; ?></td>
        <?php } ?>
        </tr>
      
      <tr>
        <td align="left">车上人员(乘客单个座位2万事故责任限额)责任险：</td>
        <?php foreach ($output['renyuan'] as $key => $value){ 
          if($value['compensate']!=2 || $value['staff']!='ck') continue;
        ?>
        <td align="left"><?php echo $value['price']; ?></td>
        <?php } ?>
        </tr>
      
      <tr>
        <td align="left">车上人员(乘客单个座位3万事故责任限额)责任险：</td>
        <?php foreach ($output['renyuan'] as $key => $value){ 
          if($value['compensate']!=3 || $value['staff']!='ck') continue;
        ?>
        <td align="left"><?php echo $value['price']; ?></td>
        <?php } ?>
        </tr>
      
      <tr>
        <td align="left">车上人员(乘客单个座位4万事故责任限额)责任险：</td>
        <?php foreach ($output['renyuan'] as $key => $value){ 
          if($value['compensate']!=4 || $value['staff']!='ck') continue;
        ?>
        <td align="left"><?php echo $value['price']; ?></td>
        <?php } ?>
        </tr>
      
      <tr>
        <td align="left">车上人员(乘客单个座位5万事故责任限额)责任险：</td>
        <?php foreach ($output['renyuan'] as $key => $value){ 
          if($value['compensate']!=5 || $value['staff']!='ck') continue;
        ?>
        <td align="left"><?php echo $value['price']; ?></td>
        <?php } ?>
        </tr>
      
      
      
      <tr>
        <td align="left">玻璃单独破碎险(进口)：</td>
        <?php foreach ($output['boli'] as $key => $value){ 
          if($value['state']!='jk') continue;
        ?>
        <td align="left"><?php echo $value['price']; ?></td>
        <?php } ?>
        </tr>
      
      <tr>
        <td align="left">玻璃单独破碎险(国产)：</td>
        <?php foreach ($output['boli'] as $key => $value){ 
          if($value['state']!='gc') continue;
        ?>
        <td align="left"><?php echo $value['price']; ?></td>
        <?php } ?>
        </tr>
      
      <tr>
        <td align="left">自燃损失险：</td>
        <?php foreach ($output['ziran'] as $key => $value): ?>
          <td align="left"><?php echo $value['price']; ?></td>
        <?php endforeach ?>
        
        </tr>
      
      <tr>
        <td align="left">车身划痕险2000元赔付额度：</td>
        <?php foreach ($output['huahen'] as $key => $value){ 
          if($value['compensate']!=2000) continue;
        ?>
        <td align="left"><?php echo $value['price']; ?></td>
        <?php } ?>
        </tr>
      
      <tr>
        <td align="left">车身划痕险5000元赔付额度：</td>
        <?php foreach ($output['huahen'] as $key => $value){ 
          if($value['compensate']!=5000) continue;
        ?>
        <td align="left"><?php echo $value['price']; ?></td>
        <?php } ?>
        </tr>
      
      <tr>
        <td align="left">车身划痕险10000元赔付额度：</td>
        <?php foreach ($output['huahen'] as $key => $value){ 
          if($value['compensate']!=10000) continue;
        ?>
        <td align="left"><?php echo $value['price']; ?></td>
        <?php } ?>
        </tr>
      
      <tr>
        <td align="left">车身划痕险20000元赔付额度：</td>
        <?php foreach ($output['huahen'] as $key => $value){ 
          if($value['compensate']!=20000) continue;
        ?>
        <td align="left"><?php echo $value['price']; ?></td>
        <?php } ?>
        </tr>
      
      <tr>
        <td align="left">不计免赔特约(机动车损失)险：</td>
        <?php foreach ($output['csx'] as $key => $value): ?>
          <td align="left"><?php echo $value['bjm_price']; ?></td>
        <?php endforeach ?>
        </tr>
      <tr>
        <td align="left">不计免赔特约(机动车盗抢)险：</td>
        <?php foreach ($output['daoqiang'] as $key => $value): ?>
          <td align="left"><?php echo $value['bjm_price']; ?></td>
        <?php endforeach ?>
        
        </tr>
      
      <tr>
        <td align="left">不计免赔特约(第三者责任5万元赔付额度)险：</td>
        <?php foreach ($output['sanzhe'] as $key => $value){ 
          if($value['compensate']!=5) continue;
        ?>
        <td align="left"><?php echo $value['bjm_price']; ?></td>
        <?php } ?>
        </tr>
      
      <tr>
        <td align="left">不计免赔特约(第三者责任10万元赔付额度)险：</td>
        <?php foreach ($output['sanzhe'] as $key => $value){ 
          if($value['compensate']!=10) continue;
        ?>
        <td align="left"><?php echo $value['bjm_price']; ?></td>
        <?php } ?>
        </tr>
      
      <tr>
        <td align="left">不计免赔特约(第三者责任15万元赔付额度)险：</td>
        <?php foreach ($output['sanzhe'] as $key => $value){ 
          if($value['compensate']!=15) continue;
        ?>
        <td align="left"><?php echo $value['bjm_price']; ?></td>
        <?php } ?>
        </tr>
      
      <tr>
        <td align="left">不计免赔特约(第三者责任20万元赔付额度)险：</td>
        <?php foreach ($output['sanzhe'] as $key => $value){ 
          if($value['compensate']!=20) continue;
        ?>
        <td align="left"><?php echo $value['bjm_price']; ?></td>
        <?php } ?>
        </tr>
      
      <tr>
        <td align="left">不计免赔特约(第三者责任30万元赔付额度)险：</td>
        <?php foreach ($output['sanzhe'] as $key => $value){ 
          if($value['compensate']!=30) continue;
        ?>
        <td align="left"><?php echo $value['bjm_price']; ?></td>
        <?php } ?>
        </tr>
      
      <tr>
        <td align="left">不计免赔特约(第三者责任50万元赔付额度)险：</td>
        <?php foreach ($output['sanzhe'] as $key => $value){ 
          if($value['compensate']!=50) continue;
        ?>
        <td align="left"><?php echo $value['bjm_price']; ?></td>
        <?php } ?>
        </tr>
      
      <tr>
        <td align="left">不计免赔特约(第三者责任100万元赔付额度)险：</td>
        <?php foreach ($output['sanzhe'] as $key => $value){ 
          if($value['compensate']!=100) continue;
        ?>
        <td align="left"><?php echo $value['bjm_price']; ?></td>
        <?php } ?>
        </tr>
      
      
      
      <tr>
        <td align="left">不计免赔特约(车上人员(司机1万事故责任限额))险：</td>
        <?php foreach ($output['renyuan'] as $key => $value){ 
          if($value['compensate']!=1 || $value['staff']!='sj') continue;
        ?>
        <td align="left"><?php echo $value['bjm_price']; ?></td>
        <?php } ?>
        </tr>
      
      <tr>
        <td align="left">不计免赔特约(车上人员(司机2万事故责任限额))险：</td>
        <?php foreach ($output['renyuan'] as $key => $value){ 
          if($value['compensate']!=2 || $value['staff']!='sj') continue;
        ?>
        <td align="left"><?php echo $value['bjm_price']; ?></td>
        <?php } ?>
        </tr>
      
      <tr>
        <td align="left">不计免赔特约(车上人员(司机3万事故责任限额))险：</td>
        <?php foreach ($output['renyuan'] as $key => $value){ 
          if($value['compensate']!=3 || $value['staff']!='sj') continue;
        ?>
        <td align="left"><?php echo $value['bjm_price']; ?></td>
        <?php } ?>
        </tr>
      
      <tr>
        <td align="left">不计免赔特约(车上人员(司机4万事故责任限额))险：</td>
        <?php foreach ($output['renyuan'] as $key => $value){ 
          if($value['compensate']!=4 || $value['staff']!='sj') continue;
        ?>
        <td align="left"><?php echo $value['bjm_price']; ?></td>
        <?php } ?>
        </tr>
      
      <tr>
        <td align="left">不计免赔特约(车上人员(司机5万事故责任限额))险：</td>
        <?php foreach ($output['renyuan'] as $key => $value){ 
          if($value['compensate']!=5 || $value['staff']!='sj') continue;
        ?>
        <td align="left"><?php echo $value['bjm_price']; ?></td>
        <?php } ?>
        </tr>
      
      
      
      <tr>
        <td align="left">不计免赔特约(车上人员(乘客1万事故责任限额))险：</td>
        <?php foreach ($output['renyuan'] as $key => $value){ 
          if($value['compensate']!=1 || $value['staff']!='ck') continue;
        ?>
        <td align="left"><?php echo $value['bjm_price']; ?></td>
        <?php } ?>
        </tr>
      
      <tr>
        <td align="left">不计免赔特约(车上人员(乘客2万事故责任限额))险：</td>
        <?php foreach ($output['renyuan'] as $key => $value){ 
          if($value['compensate']!=2 || $value['staff']!='ck') continue;
        ?>
        <td align="left"><?php echo $value['bjm_price']; ?></td>
        <?php } ?>
        </tr>
      
      <tr>
        <td align="left">不计免赔特约(车上人员(乘客3万事故责任限额))险：</td>
        <?php foreach ($output['renyuan'] as $key => $value){ 
          if($value['compensate']!=3 || $value['staff']!='ck') continue;
        ?>
        <td align="left"><?php echo $value['bjm_price']; ?></td>
        <?php } ?>
        </tr>
      
      <tr>
        <td align="left">不计免赔特约(车上人员(乘客4万事故责任限额))险：</td>
        <?php foreach ($output['renyuan'] as $key => $value){ 
          if($value['compensate']!=4 || $value['staff']!='ck') continue;
        ?>
        <td align="left"><?php echo $value['bjm_price']; ?></td>
        <?php } ?>
        </tr>
      
      <tr>
        <td align="left">不计免赔特约(车上人员(乘客5万事故责任限额))险：</td>
        <?php foreach ($output['renyuan'] as $key => $value){ 
          if($value['compensate']!=5 || $value['staff']!='ck') continue;
        ?>
        <td align="left"><?php echo $value['bjm_price']; ?></td>
        <?php } ?>
        </tr>
      
      
      
      <tr>
        <td align="left">不计免赔特约(车身划痕2000元赔付额度)险：</td>
        <?php foreach ($output['huahen'] as $key => $value){ 
          if($value['compensate']!=2000) continue;
        ?>
        <td align="left"><?php echo $value['bjm_price']; ?></td>
        <?php } ?>
        </tr>
      
      <tr>
        <td align="left">不计免赔特约(车身划痕5000元赔付额度)险：</td>
        <?php foreach ($output['huahen'] as $key => $value){ 
          if($value['compensate']!=5000) continue;
        ?>
        <td align="left"><?php echo $value['bjm_price']; ?></td>
        <?php } ?>
        </tr>
      
      <tr>
        <td align="left">不计免赔特约(车身划痕10000元赔付额度)险：</td>
        <?php foreach ($output['huahen'] as $key => $value){ 
          if($value['compensate']!=10000) continue;
        ?>
        <td align="left"><?php echo $value['bjm_price']; ?></td>
        <?php } ?>
        </tr>
      
      <tr>
        <td align="left">不计免赔特约(车身划痕20000元赔付额度)险：</td>
        <?php foreach ($output['huahen'] as $key => $value){ 
          if($value['compensate']!=20000) continue;
        ?>
        <td align="left"><?php echo $value['bjm_price']; ?></td>
        <?php } ?>
        </tr>
    </tbody>
  </table>
      </td>
    </tr>
      </thead>
  <tbody>
  
  
  </tbody>
</table>
<?php endif ?>
