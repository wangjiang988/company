<?php defined('InHG') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>系统金额设置</h3>
      <?php echo $output['top_link'];?>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="post" name="form1">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label for="gdbzj">初始固定保证金:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="gdbzj" name="gdbzj" value="<?php echo $output['list_setting']['gdbzj'];?>" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform">经销商代理入驻初始保证金</span></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label for="chengyijin">诚意金:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="chengyijin" name="chengyijin" value="<?php echo $output['list_setting']['chengyijin'];?>" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform">会员交付诚意金</span></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="qianyijin">歉意金:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="qianyijin" name="qianyijin" value="<?php echo $output['list_setting']['qianyijin'];?>" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform">退还会员歉意金</span></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="taxation_quotiety">税务系统:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="taxation_quotiety" name="taxation_quotiety" value="<?php echo $output['list_setting']['taxation_quotiety'];?>" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform">税务系数</span></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="hwache_price_immobilisation_factor">华车车价固定因子:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="hwache_price_immobilisation_factor" name="hwache_price_immobilisation_factor" value="<?php echo $output['list_setting']['hwache_price_immobilisation_factor'];?>" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform">华车车价固定因子</span></td>
        </tr>
      </tbody>
      <tfoot id="submit-holder">
        <tr class="tfoot">
          <td colspan="2" ><a href="JavaScript:void(0);" class="btn" onclick="document.form1.submit()"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script type="text/javascript">

$(function(){


});
</script>
