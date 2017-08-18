<?php
/**
 * 经销商代理
 * 报价保险添加模板
 */
defined('InHG') or exit('Access Invalid!');
?>

<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<style>
  .inline{display:inline-block;*display: inline;*zoom: 1;vertical-align: top;}
  .f_m{margin-top: 10px;}
  .f_m_l{}
  .feiyong_t{}
</style>

<div class="item-publish">
  <form action="<?php echo urlShop('store_baoxian', 'edit'); ?>" id="add" method="post">
    <input type="hidden" value="ok" name="form_submit">
    <input type="hidden" value="<?php echo $output['baoxian_id']; ?>" name="baoxian_id">
    <div class="ncsc-form-goods">
    <dl>
      <dt><i class="required">*</i>机动车损失险：</dt>
      <dd>
        <p>基础保费+新车购置价×费率</p>
        <table cellspacing="0" cellpadding="0" border="0" class="spec_table">
          <thead>
            <tr>
              <th nctype="spec_name_1">项目</th>
              <th class="w120">个人非营运(6座以下)</th>
              <th class="w120">个人非营运(6~10座)</th>
              <th class="w120">公司非营运(6座以下)</th>
              <th class="w120">公司非营运(6~10座)</th>
            </tr>
          </thead>
          <tbody>
                                    <tr>
              <td>
                基础保费              </td>
                <?php foreach ($output['csx'] as $key => $value) { ?>
              <td>
                <input type="text" name="chesun[<?php echo $value['id']; ?>][base]" class="text price w80" value="<?php echo $value['base']; ?>"><em class="add-on">元</em>
              </td>
              <?php } ?>
              
            </tr>
                                    <tr>
              <td>
                费率              </td>
                <?php foreach ($output['csx'] as $key => $value) { ?>
              <td>
                <input type="text" name="chesun[<?php echo $value['id']; ?>][rate]" class="text price w80" value="<?php echo $value['rate']; ?>"><em class="add-on">%</em>
              </td>
              <?php } ?>
              
            </tr>
                      </tbody>
        </table>
      </dd>
    </dl>
    <dl>
    <dl>
      <dt><i class="required">*</i>机动车盗抢险：</dt>
      <dd>
        <p>基础保费+新车购置价×费率</p>
        <table cellspacing="0" cellpadding="0" border="0" class="spec_table">
          <thead>
            <tr>
              <th nctype="spec_name_1">项目</th>
              <th class="w120">个人非营运(6座以下)</th>
              <th class="w120">个人非营运(6~10座)</th>
              <th class="w120">公司非营运(6座以下)</th>
              <th class="w120">公司非营运(6~10座)</th>
            </tr>
          </thead>
          <tbody>
                                    <tr>
              <td>
                基础保费              </td>
              <?php foreach ($output['dqx'] as $key => $value) { ?>  
              <td>
                <input type="text" name="daoqiang[<?php echo $value['id']; ?>][base]" class="text price w80" value="<?php echo $value['base']; ?>"><em class="add-on">元</em>
              </td>
              <?php } ?>
            </tr>
                                    <tr>
              <td>
                费率              </td>
              <?php foreach ($output['dqx'] as $key => $value) { ?>   
              <td>
                <input type="text" name="daoqiang[<?php echo $value['id']; ?>][rate]" class="text price w80" value="<?php echo $value['rate']; ?>"><em class="add-on">%</em>
              </td>
              <?php } ?>
              
            </tr>
                      </tbody>
        </table>
      </dd>
    </dl>
    <dl>
    <dl>
      <dt><i class="required">*</i>第三者责任险：</dt>
      <dd>
        <p>固定值，按赔付额度查找确定</p>
        <table cellspacing="0" cellpadding="0" border="0" class="spec_table">
          <thead>
            <tr>
              <th nctype="spec_name_1">项目</th>
              <th class="w120">个人非营运(6座以下)</th>
              <th class="w120">个人非营运(6~10座)</th>
              <th class="w120">公司非营运(6座以下)</th>
              <th class="w120">公司非营运(6~10座)</th>
            </tr>
          </thead>
          <tbody>
                                    <tr>
              <td>
                <input type="hidden" name="">5万元赔付额度              </td>
              <?php foreach ($output['sanzhe'] as $key => $value) { 
                  if($value['base']!=5) continue;
                ?>   
              <td>
                <input type="text" name="sanzhe[<?php echo $value['id']; ?>]"  value="<?php echo $value['price']; ?>" class="text price w80"><em class="add-on">元</em>
              </td>
              <?php } ?>
              
            </tr>
                        <tr>
              <td>
                <input type="hidden" name="">10万元赔付额度              </td>
              <?php foreach ($output['sanzhe'] as $key => $value) { 
                  if($value['base']!=10) continue;
                ?> 
              <td>
                <input type="text" name="sanzhe[<?php echo $value['id']; ?>]" value="<?php echo $value['price']; ?>" class="text price w80"><em class="add-on">元</em>
              </td>
              <?php } ?>
              
            </tr>
                        <tr>
              <td>
                <input type="hidden" name="">15万元赔付额度              </td>
                <?php foreach ($output['sanzhe'] as $key => $value) { 
                  if($value['base']!=15) continue;
                ?>
              <td>
                <input type="text" name="sanzhe[<?php echo $value['id']; ?>]" value="<?php echo $value['price']; ?>" class="text price w80"><em class="add-on">元</em>
              </td>
              <?php } ?>
              
            </tr>
                        <tr>
              <td>
                <input type="hidden" name="">20万元赔付额度              </td>
                <?php foreach ($output['sanzhe'] as $key => $value) { 
                  if($value['base']!=20) continue;
                ?>
              <td>
                <input type="text" name="sanzhe[<?php echo $value['id']; ?>]" value="<?php echo $value['price']; ?>" class="text price w80"><em class="add-on">元</em>
              </td>
              <?php } ?>
              
            </tr>
                        <tr>
              <td>
                <input type="hidden" name="">30万元赔付额度              </td>
                <?php foreach ($output['sanzhe'] as $key => $value) { 
                  if($value['base']!=30) continue;
                ?>
              <td>
                <input type="text" name="sanzhe[<?php echo $value['id']; ?>]" value="<?php echo $value['price']; ?>" class="text price w80"><em class="add-on">元</em>
              </td>
              <?php } ?>
              
            </tr>
                        <tr>
              <td>
                <input type="hidden" name="">50万元赔付额度              </td>
               <?php foreach ($output['sanzhe'] as $key => $value) { 
                  if($value['base']!=50) continue;
                ?> 
              <td>
                <input type="text" name="sanzhe[<?php echo $value['id']; ?>]" value="<?php echo $value['price']; ?>" class="text price w80"><em class="add-on">元</em>
              </td>
              <?php } ?>
             
            </tr>
                        <tr>
              <td>
                <input type="hidden" name="">100万元赔付额度              </td>
              <?php foreach ($output['sanzhe'] as $key => $value) { 
                  if($value['base']!=100) continue;
                ?> 
              <td>
                <input type="text" name="sanzhe[<?php echo $value['id']; ?>]" value="<?php echo $value['price']; ?>" class="text price w80"><em class="add-on">元</em>
              </td>
              <?php } ?>
            </tr>
                      </tbody>
        </table>
      </dd>
    </dl>
    <dl>
    <dl>
      <dt><i class="required">*</i>车上人员责任险：</dt>
      <dd>
        <p>每次事故责任限额×费率(×投保座位数)</p>
        <table cellspacing="0" cellpadding="0" border="0" class="spec_table">
          <thead>
            <tr>
              <th nctype="spec_name_1">项目</th>
              <th class="w120">个人非营运(6座以下)</th>
              <th class="w120">个人非营运(6~10座)</th>
              <th class="w120">公司非营运(6座以下)</th>
              <th class="w120">公司非营运(6~10座)</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                司机_每座限额1万元_费率              </td>
                <?php foreach ($output['renyuan'] as $key => $value) {
                  
                    if($value['base']!=1 || $value['title']!='司机') continue;

                  ?>
              <td>
                <input type="text" name="renyuan[<?php echo $value['id']; ?>]" value="<?php echo $value['rate']; ?>" class="text price w80"><em class="add-on">%</em>
              </td>
              <?php } ?>
              
              
            </tr>
                        <tr>
              <td>
                司机_每座限额2万元_费率              </td>
              <?php foreach ($output['renyuan'] as $key => $value) {
                  
                    if($value['base']!=2 || $value['title']!='司机') continue;

                  ?>
              <td>
                <input type="text" name="renyuan[<?php echo $value['id']; ?>]" value="<?php echo $value['rate']; ?>" class="text price w80"><em class="add-on">%</em>
              </td>
              <?php } ?>
              
            </tr>
                        <tr>
              <td>
                司机_每座限额3万元_费率              </td>
              
              <?php foreach ($output['renyuan'] as $key => $value) {
                  
                    if($value['base']!=3 || $value['title']!='司机') continue;

                  ?>
              <td>
                <input type="text" name="renyuan[<?php echo $value['id']; ?>]" value="<?php echo $value['rate']; ?>" class="text price w80"><em class="add-on">%</em>
              </td>
              <?php } ?>
            </tr>
                        <tr>
              <td>
                司机_每座限额4万元_费率              </td>
              
              <?php foreach ($output['renyuan'] as $key => $value) {
                  
                    if($value['base']!=4 || $value['title']!='司机') continue;

                  ?>
              <td>
                <input type="text" name="renyuan[<?php echo $value['id']; ?>]" value="<?php echo $value['rate']; ?>" class="text price w80"><em class="add-on">%</em>
              </td>
              <?php } ?>
            </tr>
                        <tr>
              <td>
                司机_每座限额5万元_费率              </td>
              <?php foreach ($output['renyuan'] as $key => $value) {
                  
                    if($value['base']!=5 || $value['title']!='司机') continue;

                  ?>
              <td>
                <input type="text" name="renyuan[<?php echo $value['id']; ?>]" value="<?php echo $value['rate']; ?>" class="text price w80"><em class="add-on">%</em>
              </td>
              <?php } ?>
              
            </tr>
                        <tr>
              <td>
                乘客_每座限额1万元_费率              </td>
              
              <?php foreach ($output['renyuan'] as $key => $value) {
                  
                    if($value['base']!=1 || $value['title']!='乘客') continue;

                  ?>
              <td>
                <input type="text" name="renyuan[<?php echo $value['id']; ?>]" value="<?php echo $value['rate']; ?>" class="text price w80"><em class="add-on">%</em>
              </td>
              <?php } ?>
            </tr>
                        <tr>
              <td>
                乘客_每座限额2万元_费率              </td>
                <?php foreach ($output['renyuan'] as $key => $value) {
                  
                    if($value['base']!=2 || $value['title']!='乘客') continue;

                  ?>
              <td>
                <input type="text" name="renyuan[<?php echo $value['id']; ?>]" value="<?php echo $value['rate']; ?>" class="text price w80"><em class="add-on">%</em>
              </td>
              <?php } ?>

            </tr>
                        <tr>
              <td>
                乘客_每座限额3万元_费率              </td>
              
              <?php foreach ($output['renyuan'] as $key => $value) {
                  
                    if($value['base']!=3 || $value['title']!='乘客') continue;

                  ?>
              <td>
                <input type="text" name="renyuan[<?php echo $value['id']; ?>]" value="<?php echo $value['rate']; ?>" class="text price w80"><em class="add-on">%</em>
              </td>
              <?php } ?>
            </tr>
                        <tr>
              <td>
                乘客_每座限额4万元_费率              </td>
              
              <?php foreach ($output['renyuan'] as $key => $value) {
                  
                    if($value['base']!=4 || $value['title']!='乘客') continue;

                  ?>
              <td>
                <input type="text" name="renyuan[<?php echo $value['id']; ?>]" value="<?php echo $value['rate']; ?>" class="text price w80"><em class="add-on">%</em>
              </td>
              <?php } ?>
            </tr>
                        <tr>
              <td>
                乘客_每座限额5万元_费率              </td>
              
              <?php foreach ($output['renyuan'] as $key => $value) {
                  
                    if($value['base']!=5 || $value['title']!='乘客') continue;

                  ?>
              <td>
                <input type="text" name="renyuan[<?php echo $value['id']; ?>]" value="<?php echo $value['rate']; ?>" class="text price w80"><em class="add-on">%</em>
              </td>
              <?php } ?>
            </tr>
                      </tbody>
        </table>
      </dd>
    </dl>
    <dl>
    <dl>
      <dt><i class="required">*</i>玻璃单独破碎险：</dt>
      <dd>
        <p>新车购置价×费率</p>
        <table cellspacing="0" cellpadding="0" border="0" class="spec_table">
          <thead>
            <tr>
              <th nctype="spec_name_1">项目</th>
              <th class="w120">个人非营运(6座以下)</th>
              <th class="w120">个人非营运(6~10座)</th>
              <th class="w120">公司非营运(6座以下)</th>
              <th class="w120">公司非营运(6~10座)</th>
            </tr>
          </thead>
          <tbody>
                                    <tr>
              <td>
                进口_费率              </td>
                <?php foreach ($output['boli'] as $key => $value) {
                    if($value['title']!='进口') continue;
                  ?>
              <td>
                <input type="text" name="boli[<?php echo $value['id']; ?>]" value="<?php echo $value['rate']; ?>" class="text price w80"><em class="add-on">%</em>
              </td>
              <?php } ?>
              
            </tr>
                        <tr>
              <td>
                国产_费率              </td>
              <?php foreach ($output['boli'] as $key => $value) {
                    if($value['title']!='国产') continue;
                  ?>
              <td>
                <input type="text" name="boli[<?php echo $value['id']; ?>]" value="<?php echo $value['rate']; ?>" class="text price w80"><em class="add-on">%</em>
              </td>
              <?php } ?>
            </tr>
                      </tbody>
        </table>
      </dd>
    </dl>
    <dl>
    <dl>
      <dt><i class="required">*</i>自燃损失险：</dt>
      <dd>
        <p>新车购置价×费率</p>
        <table cellspacing="0" cellpadding="0" border="0" class="spec_table">
          <thead>
            <tr>
              <th nctype="spec_name_1">项目</th>
              <th class="w120">个人非营运(6座以下)</th>
              <th class="w120">个人非营运(6~10座)</th>
              <th class="w120">公司非营运(6座以下)</th>
              <th class="w120">公司非营运(6~10座)</th>
            </tr>
          </thead>
          <tbody>
                                    <tr>
              <td>
                费率              </td>
                <?php foreach ($output['ziran'] as $key => $value) {
                  ?>
              <td>
                <input type="text" name="ziran[<?php echo $value['id']; ?>]" value="<?php echo $value['rate']; ?>" class="text price w80"><em class="add-on">%</em>
              </td>
              <?php } ?>
              
            </tr>
                      </tbody>
        </table>
      </dd>
    </dl>
    <dl>
    <dl>
      <dt><i class="required">*</i>车身划痕损失险：</dt>
      <dd>
        <p>固定值，按新车购置价和赔付额度查找确定</p>
        <table cellspacing="0" cellpadding="0" border="0" class="spec_table">
          <thead>
            <tr>
              <th nctype="spec_name_1">项目</th>
              <th class="w120">个人非营运(6座以下)</th>
              <th class="w120">个人非营运(6~10座)</th>
              <th class="w120">公司非营运(6座以下)</th>
              <th class="w120">公司非营运(6~10座)</th>
            </tr>
          </thead>
          <tbody>
                                    <tr>
              <td>
                30万以下_赔付额度0.2万元_赔付额度              </td>

               <?php foreach ($output['huahen'] as $key => $value) {
                    if($value['peifu']!='2000' || $value['title']!='30万以下') continue;
                  ?> 
              <td>
                <input type="text" name="huahen[<?php echo $value['id']; ?>]" value="<?php echo $value['price']; ?>" class="text price w80"><em class="add-on">元</em>
              </td>
              <?php } ?>
              
            </tr>
                        <tr>
              <td>
                30万以下_赔付额度0.5万元_赔付额度              </td>
              <?php foreach ($output['huahen'] as $key => $value) {
                    if($value['peifu']!='5000' || $value['title']!='30万以下') continue;
                  ?> 
              <td>
                <input type="text" name="huahen[<?php echo $value['id']; ?>]" value="<?php echo $value['price']; ?>" class="text price w80"><em class="add-on">元</em>
              </td>
              <?php } ?>
            </tr>
                        <tr>
              <td>
                30万以下_赔付额度1万元_赔付额度              </td>
              <?php foreach ($output['huahen'] as $key => $value) {
                    if($value['peifu']!='10000' || $value['title']!='30万以下') continue;
                  ?> 
              <td>
                <input type="text" name="huahen[<?php echo $value['id']; ?>]" value="<?php echo $value['price']; ?>" class="text price w80"><em class="add-on">元</em>
              </td>
              <?php } ?>
            </tr>
                        <tr>
              <td>
                30万以下_赔付额度2万元_赔付额度              </td>
              <?php foreach ($output['huahen'] as $key => $value) {
                    if($value['peifu']!='20000' || $value['title']!='30万以下') continue;
                  ?> 
              <td>
                <input type="text" name="huahen[<?php echo $value['id']; ?>]" value="<?php echo $value['price']; ?>" class="text price w80"><em class="add-on">元</em>
              </td>
              <?php } ?>
            </tr>
                        <tr>
              <td>
                30~50万_赔付额度0.2万元_赔付额度              </td>
              <?php foreach ($output['huahen'] as $key => $value) {
                    if($value['peifu']!='2000' || $value['title']!='30~50万') continue;
                  ?> 
              <td>
                <input type="text" name="huahen[<?php echo $value['id']; ?>]" value="<?php echo $value['price']; ?>" class="text price w80"><em class="add-on">元</em>
              </td>
              <?php } ?>
            </tr>
                        <tr>
              <td>
                30~50万_赔付额度0.5万元_赔付额度              </td>
              <?php foreach ($output['huahen'] as $key => $value) {
                    if($value['peifu']!='5000' || $value['title']!='30~50万') continue;
                  ?> 
              <td>
                <input type="text" name="huahen[<?php echo $value['id']; ?>]" value="<?php echo $value['price']; ?>" class="text price w80"><em class="add-on">元</em>
              </td>
              <?php } ?>
            </tr>
                        <tr>
              <td>
                30~50万_赔付额度1万元_赔付额度              </td>
                 <?php foreach ($output['huahen'] as $key => $value) {
                    if($value['peifu']!='10000' || $value['title']!='30~50万') continue;
                  ?> 
              <td>
                <input type="text" name="huahen[<?php echo $value['id']; ?>]" value="<?php echo $value['price']; ?>" class="text price w80"><em class="add-on">元</em>
              </td>
              <?php } ?>

            </tr>
                        <tr>
              <td>
                30~50万_赔付额度2万元_赔付额度              </td>
                <?php foreach ($output['huahen'] as $key => $value) {
                    if($value['peifu']!='20000' || $value['title']!='30~50万') continue;
                  ?> 
              <td>
                <input type="text" name="huahen[<?php echo $value['id']; ?>]" value="<?php echo $value['price']; ?>" class="text price w80"><em class="add-on">元</em>
              </td>
              <?php } ?>

            </tr>
                        <tr>
              <td>
                50万以上_赔付额度0.2万元_赔付额度              </td>
              <?php foreach ($output['huahen'] as $key => $value) {
                    if($value['peifu']!='2000' || $value['title']!='50万以上') continue;
                  ?> 
              <td>
                <input type="text" name="huahen[<?php echo $value['id']; ?>]" value="<?php echo $value['price']; ?>" class="text price w80"><em class="add-on">元</em>
              </td>
              <?php } ?>
            </tr>
                        <tr>
              <td>
                50万以上_赔付额度0.5万元_赔付额度              </td>
              <?php foreach ($output['huahen'] as $key => $value) {
                    if($value['peifu']!='5000' || $value['title']!='50万以上') continue;
                  ?> 
              <td>
                <input type="text" name="huahen[<?php echo $value['id']; ?>]" value="<?php echo $value['price']; ?>" class="text price w80"><em class="add-on">元</em>
              </td>
              <?php } ?>
            </tr>
                        <tr>
              <td>
                50万以上_赔付额度1万元_赔付额度              </td>
              <?php foreach ($output['huahen'] as $key => $value) {
                    if($value['peifu']!='10000' || $value['title']!='50万以上') continue;
                  ?> 
              <td>
                <input type="text" name="huahen[<?php echo $value['id']; ?>]" value="<?php echo $value['price']; ?>" class="text price w80"><em class="add-on">元</em>
              </td>
              <?php } ?>
            </tr>
                        <tr>
              <td>
                50万以上_赔付额度2万元_赔付额度              </td>
              <?php foreach ($output['huahen'] as $key => $value) {
                    if($value['peifu']!='20000' || $value['title']!='50万以上') continue;
                  ?> 
              <td>
                <input type="text" name="huahen[<?php echo $value['id']; ?>]" value="<?php echo $value['price']; ?>" class="text price w80"><em class="add-on">元</em>
              </td>
              <?php } ?>
            </tr>
                      </tbody>
        </table>
      </dd>
    </dl>
    <dl>
    <dl>
      <dt><i class="required">*</i>不计免赔特约险：</dt>
      <dd>
        <p>适用本条款的险种标准保费×费率</p>
        <table cellspacing="0" cellpadding="0" border="0" class="spec_table">
          <thead>
            <tr>
              <th nctype="spec_name_1">项目</th>
              <th class="w120">个人非营运(6座以下)</th>
              <th class="w120">个人非营运(6~10座)</th>
              <th class="w120">公司非营运(6座以下)</th>
              <th class="w120">公司非营运(6~10座)</th>
            </tr>
          </thead>
          <tbody>
                                    <tr>
              <td>
                <input type="hidden" name="">机动车损失险保险的种类              </td>
              <?php foreach ($output['bujimian'] as $key => $value) { 
                  if($value['baoxian_type']!='chesun') continue;
                ?> 
              <td>
                <input type="text" name="bujimian[<?php echo $value['id']; ?>]" value="<?php echo $value['rate']; ?>" class="text price w80"><em class="add-on">%</em>
              </td>
                <?php } ?>
              
            </tr>
                        <tr>
              <td>
                <input type="hidden" name="">机动车盗抢险保险的种类              </td>
              <?php foreach ($output['bujimian'] as $key => $value) { 
                  if($value['baoxian_type']!='daoqiang') continue;
                ?> 
              <td>
                <input type="text" name="bujimian[<?php echo $value['id']; ?>]" value="<?php echo $value['rate']; ?>" class="text price w80"><em class="add-on">%</em>
              </td>
                <?php } ?>
            </tr>
                        <tr>
              <td>
                <input type="hidden" name="">第三者责任险保险的种类              </td>
              <?php foreach ($output['bujimian'] as $key => $value) { 
                  if($value['baoxian_type']!='sanzhe') continue;
                ?> 
              <td>
                <input type="text" name="bujimian[<?php echo $value['id']; ?>]" value="<?php echo $value['rate']; ?>" class="text price w80"><em class="add-on">%</em>
              </td>
                <?php } ?>
            </tr>
                        <tr>
              <td>
                <input type="hidden" name="">车上人员责任险保险的种类              </td>
              <?php foreach ($output['bujimian'] as $key => $value) { 
                  if($value['baoxian_type']!='renyuan') continue;
                ?> 
              <td>
                <input type="text" name="bujimian[<?php echo $value['id']; ?>]" value="<?php echo $value['rate']; ?>" class="text price w80"><em class="add-on">%</em>
              </td>
                <?php } ?>
            </tr>
                        <tr>
              <td>
                <input type="hidden" name="">车身划痕损失险保险的种类              </td>
              <?php foreach ($output['bujimian'] as $key => $value) { 
                  if($value['baoxian_type']!='huahen') continue;
                ?> 
              <td>
                <input type="text" name="bujimian[<?php echo $value['id']; ?>]" value="<?php echo $value['rate']; ?>" class="text price w80"><em class="add-on">%</em>
              </td>
                <?php } ?>
            </tr>
                      </tbody>
        </table>
      </dd>
    </dl>
    <dl>
    </dl></dl></dl></dl></dl></dl></dl></dl></div>
    <div class="bottom tc hr32">
      <label class="submit-border">
        <input type="submit" value="保存" class="submit">
      </label>
    </div>
  </form>
</div>
