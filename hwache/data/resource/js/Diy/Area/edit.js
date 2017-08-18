/**
 * Created by jerry on 2016/12/24.
 */
$(document).ready(function(){
    $('#area_form').validate({
        rules : {
            "tax[]" : {
                required : true,
                number   : true
            }
        },
        messages : {
            "tax[]" : {
                required   : '金额不能为空！',
                number     : '金额必须是数字！'
            }

        },
        //设置错误信息存放标签
        errorElement: "label",
        //指定错误信息位置
        errorPlacement: function (error, element) {
            if (element.is(':radio') || element.is(':checkbox')) {
                var eid = element.attr('name');
            }
            error.appendTo(element.parent());
        },
        //设置验证触发事件
        focusInvalid: true,
        //设置验证成功提示格式
        success:function(e)
        {
            e.html("&nbsp;").addClass("valid").text('ok');
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
});

function addFiles(){
    var html = '<tr><td>'+lenght+'</td>';
    html +='<td><input type="text" name="fiels[]" id=""></td>';
    html +='<td><a href="javascript:;" onclick="removeOther(this);"> 移除 </a></td></tr>';
    lenght ++;
    $('#lilstFile').append(html);
}

function removeOther(obj){
    $(obj).parent().parent().bind().remove();
}

function delOther(_key){
    var _areaId = $('#area_id').val();
    var tcIndex = layer.confirm('您确认要删除吗？', {
        btn: ['确定','取消'] //按钮
    }, function(){
        delOtherIndex(_areaId,_key);
    }, function(){
        layer.close(tcIndex);
    });
}

/**
 * 删除处理
 * @param _sellerId
 * @param _key
 */
function delOtherIndex(_sellerId,_key){
    $.ajax({
        type : "post",
        url  : del_url,
        data : {id:_sellerId,key:_key},
        dataType : "json",
        success : function(_result){
            console.log(_result);
            if(_result.Success == 1){
                layerBack();
            }else{
                layer.alert('删除失败！');
            }
        },error:function (XMLHttpRequest, textStatus, errorThrown) {
            console.log("XMLHttpRequest:"+XMLHttpRequest);
            console.log("textStatus:"+textStatus);
            console.log("errorThrown:"+errorThrown);
        }
    });
}
/**
 * 关闭弹窗并刷新页面
 */
function layerBack(){
    layer.closeAll();
    window.location.href=window.location.href;
}