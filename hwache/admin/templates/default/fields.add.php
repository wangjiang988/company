<?php defined('InHG') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>自定义字段管理</h3>
      <ul class="tab-base">
        <li><a href="index.php?act=fields" ><span>管理</span></a></li>
        <li><a href="javascript:void(0);" class="current"><span>新增</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="fields_form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td class="w108"><label class="validation">关联模块:</label></td>
          <td>
            <select name="model" id="model">
              <?php foreach ($output['carmodel'] as $k => $v) { ?>
              <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
              <?php }?>
            </select>
          </td>
          <td class="tips">请选择关联的模块</td>
        </tr>
        <tr class="noborder">
          <td class="w108"><label class="validation">字段类型:</label></td>
          <td>
            <select name="type" id="type">
              <?php foreach ($output['type'] as $k => $v) { ?>
              <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
              <?php }?>
            </select>
          </td>
          <td class="tips">请选择字段类型</td>
        </tr>
        <tr class="noborder" id="setting_tr" style="display:none;">
          <td class="w108"><label class="validation">选项列表:</label></td>
          <td><textarea name="setting" rows="6"></textarea></td>
          <td class="tips">在此输入选项值，值与值之间需换行隔开如（自动去除空白）：<br>选项1 <br>选项2</td>
        </tr>
        <tr class="noborder">
          <td class="w108"><label class="validation">字段名:</label></td>
          <td><input type="text" name="name"></td>
          <td class="tips">只能由小写英文字母和下划线组成，并且以字母开头，不以下划线结尾,例如：article_title</td>
        </tr>
        <tr class="noborder">
          <td class="w108"><label class="validation">显示名称:</label></td>
          <td><input type="text" name="title"></td>
          <td class="tips">例如：颜色</td>
        </tr>
        <tr class="noborder">
          <td class="w108"><label class="validation">字段详细说明:</label></td>
          <td><input type="text" name="desc"></td>
          <td class="tips">添加报价详细说明该字段的意义</td>
        </tr>
        <tr class="noborder">
          <td class="w108"><label class="validation">排序:</label></td>
          <td><input type="text" name="sort" value="255"></td>
          <td class="tips"></td>
        </tr>
        <tr class="noborder">
          <td class="w108"><label class="validation">是否支持搜索:</label></td>
          <td><label><input type="radio" name="search" value="1"> 是</label>&nbsp;&nbsp;<label><input type="radio" checked="checked" value="0" name="search"> 否</label></td>
          <td class="tips">是否可以当做搜索条件参加搜索</td>
        </tr>
        <tr class="noborder">
          <td class="w108"><label class="validation">是否添加时显示:</label></td>
          <td><label><input type="radio" name="add" checked="checked" value="1"> 是</label>&nbsp;&nbsp;<label><input type="radio" value="0" name="add"> 否</label></td>
          <td class="tips">会员添加报价的时候是否显示该字段</td>
        </tr>
        <tr class="noborder">
          <td class="w108"><label class="validation">是否列表页显示:</label></td>
          <td><label><input type="radio" name="index" checked="checked" value="1"> 是</label>&nbsp;&nbsp;<label><input type="radio" value="0" name="index"> 否</label></td>
          <td class="tips">前端列表页是否显示该字段</td>
        </tr>
        <tr class="noborder">
          <td class="w108"><label class="validation">只读:</label></td>
          <td><label><input type="radio" name="readonly" value="1"> 是</label>&nbsp;&nbsp;<label><input type="radio" checked="checked" value="0" name="readonly"> 否</label></td>
          <td class="tips">添加报价,该字段只读,不能更改</td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="15"><a href="JavaScript:void(0);" class="btn" id="submitBtn"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script>
  $(function(){
    var typearr = ['radio', 'checkbox', 'select'];
    $('#type').change(function(){
      var v = $(this).val();
      var is_ok = $.inArray(v, typearr);
      if(is_ok >= 0){
        $('#setting_tr').show();
      } else {
        $('#setting_tr').hide();
      }
    });

    //按钮先执行验证再提交表单
    $("#submitBtn").click(function(){
      $("#fields_form").submit();
    });
  });
</script>