/**
 * Created by jerry on 2016/11/9.
 */

/**
 * 异步获取城市
 * @param province 省id
 * @param _target  城市显示下拉菜单id
 */
function setCity(province,_target){
    var SelectTarget = '#'+_target;
    var _options = '<option value="">--请选择城市--</option>';
    var _addr = '';
    $(SelectTarget).html('');
    $.ajax({
        type:"GET",
        url: "index.php?act=admin&op=region",
        dataType: "json",
        data:"parent="+province,
        success: function(result){
            if(result.Success == 1){
                $(result.Data).each(function(_key,_item){
                    _options += '<option value="'+_item.area_id+'">'+_item.area_name+'</option>';
                });
                $(SelectTarget).html(_options);
            }
            $(SelectTarget).html(_options);
        }
    });
}
