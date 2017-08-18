<?php defined('InHG') or exit('Access Invalid!');?>

<div class="tabmenu">
  <ul class="tab pngFix">
    <li class="active"><a href="javascript:;">添加服务专员</a></li>
  </ul>
</div>

<div class="item-publish">
  <form method="get" action="index.php">
    <input type="hidden" name="form_submit" value="ok">
    <input type="hidden" name="act" value="fuwuzhuanyuan" />
    <input type="hidden" name="op" value="updata" />
    <div class="ncsc-form-goods">
      <dl>
        <dt><i class="required">*</i>姓名：</dt>
        <dd><input type="text" class="text w300" name="name" value=""></dd>
      </dl>
      <dl>
        <dt><i class="required">*</i>手机：</dt>
        <dd><input type="text" class="text w300" name="mobile" value=""></dd>
      </dl>
      <dl>
        <dt><i class="required">*</i>备用电话：</dt>
        <dd><input type="text" class="text w300" name="tel" value=""></dd>
      </dl>

      <dl>
        <dt><i class="required">*</i>所属经销商：</dt>
        <dd>
          <select name="dealer_id" id="">
          <?php foreach ($output['jingxiaoshang'] as $key => $value): ?>
            <option value="<?php echo $value['d_id'] ?>"><?php echo $value['d_name'] ?></option>
          <?php endforeach ?>
                     </select>
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i>备注：</dt>
        <dd><textarea name="notice" id="" cols="30" rows="10"></textarea></dd>
      </dl>
      
    </div>
    <div class="bottom tc hr32">
      <label class="submit-border">
        <input type="submit" class="submit" value="提交">
      </label>
    </div>
  </form>
</div>


