<?php defined('InHG') or exit('Access Invalid!');?>

<div class="tabmenu">
  <ul class="tab pngFix">
<li class="active"><a href="">设置报价有效时间段</a></li></ul>
</div>
<form action="<?php echo urlShop('store_goods_online', 'set_time');?>" method="post">
<input type="hidden" name="form_submit" value="ok">
<input type="hidden" name="bj_id" value="<?php echo $output['bj_id']; ?>">
<table class="ncsc-table-style">
    <tr>
      <td style="text-align:left;">
      上午开始时间：
      <select name="startime1" id="">
        <option value="9:00">9:00</option>
        <option value="10:00">10:00</option>
        <option value="11:00">11:00</option>
      </select>
      结束时间:
      <select name="endtime1" id="">
        <option value="9:00">9:00</option>
        <option value="10:00">10:00</option>
        <option value="11:00">11:00</option>
      </select>
      </td>
    </tr>
    <tr>
      <td style="text-align:left;">
      下午开始时间：
      <select name="startime2" id="">
        <option value="13:00">13:00</option>
        <option value="14:00">14:00</option>
        <option value="15:00">15:00</option>
        <option value="16:00">16:00</option>
        <option value="17:00">17:00</option>
      </select>
      结束时间:
      <select name="endtime2" id="">
        <option value="13:00">13:00</option>
        <option value="14:00">14:00</option>
        <option value="15:00">15:00</option>
        <option value="16:00">16:00</option>
        <option value="17:00">17:00</option>
      </select>
      </td>
    </tr>
    <tr><td>
      <input type="submit" class="submit" value="保存">
    </td></tr>
</table>
</form>