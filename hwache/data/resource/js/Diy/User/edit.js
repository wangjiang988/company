/**
 * Created by jerry on 2016/11/21.
 */
function validateIdcart() {
    $('#idcart_form').validate({
        rules: {
            status: {required: true},
            last_name: {required: true},
            first_name: {required: true}
        },
        messages: {
            status: {required: '请选择审核结果！'},
            last_name: {required: "姓不能为空！"},
            first_name: {required: "名不能为空"}
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
        success: function (e) {
            e.html("&nbsp;").addClass("valid").text('ok');
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
}
/**
 * Created by Administrator on 2017/3/25.
 */

function showBankDialog(bank_id){
    layer.open({
        type: 2,
        title:'查看银行卡审核信息',
        skin: 'layui-layer-molv', //加上边框
        area: ['420px', '250px'],
        fixed: false, //不固定
        maxmin: true,
        content: ['index.php?act=new_user&op=bank&id='+bank_id,'no']
    });
}

/**
 * Created by Administrator on 2017/3/25.
 */

function showBankLog(log_id){
    layer.open({
        type: 2,
        title:'审核详情',
        skin: 'layui-layer-molv', //加上边框
        area: ['550px', '350px'],
        fixed: false, //不固定
        maxmin: true,
        content: ['index.php?act=new_user&op=bankLog&id='+log_id,'no']
    });
}
/**
 * 查看相册
 * @param _user_id
 * @param _img_type
 */
function showImages(_user_id,_img_type){
    var _title = (_img_type =='id_cart') ? '查看身份证图片相册' : '查看银行卡图片相册';
    layer.open({
        type: 2,
        title:_title,
        skin: 'layui-layer-molv', //加上边框
        area: ['100%', '100%'],
        fixed: false, //不固定
        maxmin: true,
        content: ['index.php?act=new_user&op=imgList&id='+_user_id+'&type='+_img_type,'no']
    });
}

function addImages(id,status){
    parent.layer.open({
        type: 2,
        title:"添加备注",
        skin: 'layui-layer-rim', //加上边框
        area: ['500px', '320px'], //宽高
        content: '/index.php?act=finance&op=ajax_common_add_comment&operation_type=23&status='+status+'&id='+id
    });
}

/**
 * 关闭弹窗并刷新页面
 */
function layerBack(){
    layer.closeAll();
    window.location.href=window.location.href;
}
/**
 * 删除确认弹窗
 * @param _key
 */
function setStatus(user_id,status){
    var _smg = (status==1) ? '您确定要恢复设置吗？' : '您确定要冻结用户吗？';
    var tcIndex = layer.confirm(_smg, {
        btn: ['确定','取消'] //按钮
    }, function(){
        updateStatus(user_id,status);
    }, function(){
        layer.close(tcIndex);
    });
}
/**
 * 删除处理
 * @param _sellerId
 * @param _key
 */
function updateStatus(user_id,status){
    $.ajax({
        type : "post",
        url  : update_status_url,
        data : {user_id:user_id,status:status},
        dataType : "json",
        success : function(_result){
            console.log(_result);
            if(_result.Success == 1 || _result.success ==1){
                layerBack();
            }else{
                layer.alert('设置失败！');
            }
        },error:function (XMLHttpRequest, textStatus, errorThrown) {
            console.log("XMLHttpRequest:"+XMLHttpRequest);
            console.log("textStatus:"+textStatus);
            console.log("errorThrown:"+errorThrown);
        }
    });
}
/**
 * 解冻或冻结账号
 * @param _value
 * @param _ismobile
 * @param _status
 */
function setDj(id,_status,_type){
    var _smg = (_type=='dj') ? '你确定要设为允许吗？':'你确定要解冻吗？';
    //_smg += (_ismobile==1) ? '用户手机号？' : '用户邮箱吗？';
    var tcIndex = layer.confirm(_smg, {
        btn: ['确定','取消'] //按钮
    }, function(){
        setDjMobileEmail(id,_status,_type);
    }, function(){
        layer.close(tcIndex);
    });
}
/**
 * 设置手机或邮件防骚扰
 * @param _value
 * @param _ismobile
 * @param _status
 */
function setDjMobileEmail(_id,_status,_type){
    $.ajax({
        type : "post",
        url  : dj_user_url,
        data : {id:_id,status:_status,freeze:_type},
        dataType : "json",
        success : function(_result){
            console.log(_result);
            layerBack();
        },error:function (XMLHttpRequest, textStatus, errorThrown) {
            layerBack();
            console.log("XMLHttpRequest:"+XMLHttpRequest);
            console.log("textStatus:"+textStatus);
            console.log("errorThrown:"+errorThrown);
        }
    });
}
/**
 * 全选、反选防骚扰复选框
 * @param obj
 */
function allCheck(obj){
    $('input[name="id[]"]').attr("checked",obj.checked);
}
/**
 * 批量解除防骚扰确认
 */
function delAll(){
    var _type = $("input[name='type']").val();
    var _smg = (_type ==0) ? '请选择需要解除免骚扰的手机号！' : '请选择需要解除免骚扰的邮件地址！';
    var isCheck = $('input[name="id[]"]').is(':checked');
    if(isCheck == false){
        layer.alert(_smg);
    }else{
        var _delsmg = (_type ==0) ? '你确定要批量解除手机号免骚扰吗！' : '你确定要批量解除邮件地址免骚扰吗！';
        delConfirm(_delsmg);
    }
}
/**
 * 触发批量删除动作
 * @param msg
 */
function delConfirm(msg){
    var delOpens = layer.confirm(msg, {
        btn: ['确定','取消'] //按钮
    }, function(){
        $('#checkForm').submit();
    }, function(){
        layer.close(delOpens);
    });
}

function addFreeze(){
    var _type = $("input[name='type']").val();
    layer.open({
        type: 2,
        title:'设置防骚扰',
        skin: 'layui-layer-molv', //加上边框
        area: ['420px', '250px'],
        fixed: false, //不固定
        maxmin: true,
        content: ['index.php?act=new_user&op=addFreeze&type='+_type,'no']
    });
}