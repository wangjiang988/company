/**
 * jQuery 级联下拉列表插件
 * version: 1.0
 *
 * @author 技安 <php360@qq.com>
 * @link   http://www.moqifei.com
 *
 * 用法:
 */
$.fn.selectLy = function(tree, options) {

  var defaults = {
    'child' : 'child',
    'id'    : 'id',
    'value' : 'value',
    'class' : 'select_ly',
    'choose': '请选择...'
  };
  var setting = $.extend({}, defaults, options);

  var that = this;

  // 内部变量
  var _var;
  this.change(function(){
    _var = $(this).val();
    $.each(tree, function(i, n){
      var id = that.attr('id') + "_",
          name = that.attr('name') + "_";
      removeNested(name);
      if (tree[i][setting.id] == _var) {
        var sle = '<select name="'+name+'" class="'+setting.class+'">';
            sle += addOption(n[setting.child]);
            sle += '</select>';
        that.after(sle);
        return false;
      };
    });
  });

  // 增加下一级下拉列表
  var addOption = function(t) {
    // var opt = '<option>'+setting.choose+'</option>';
    var opt = '';
    $.each(t, function(k, v){
      opt += '<option value="'+v[setting.id]+'">'+v[setting.value]+'</option>';
    });
    return opt;
  }

  var removeNested = function(name) {
    $("select[name^='"+ name + "']").remove();
  };

}
