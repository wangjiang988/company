/**
 * Created by jerry on 2016/11/14.
 */

// 邮政编码验证
jQuery.validator.addMethod("isZipCode", function(value, element) {
    var tel = /^[0-9]{6}$/;
    return this.optional(element) || (tel.test(value));
}, "请正确填写您的邮政编码");


// 字符验证
jQuery.validator.addMethod("stringCheck", function(value, element) {
    //var isString = /^[\u0391-\uFFE5\w]+$/;
    return this.optional(element) || isValidStr(value);
}, "只能包括中文字、英文字母、数字和下划线！");
//手机号验证
jQuery.validator.addMethod("isMobile", function(value, element) {
    var mobile = /^0{0,1}1[3|5|7|8][0-9]{9}$/;
    var length = value.length;
    return this.optional(element) || (length == 11 && mobile.test(value));
}, "请正确填写您的手机号码");
//固定电话验证
jQuery.validator.addMethod("isTel", function(value, element) {
    var tel = /^\d{3,4}-?\d{7,9}$/;    //电话号码格式010-12345678
    return this.optional(element) || (tel.test(value));
}, "请正确填写您的电话号码");
//手机和固定电话号码联合验证
jQuery.validator.addMethod("isPhone", function(value,element) {
    //var length = value.length;
    var mobile = /^0{0,1}1[3|5|7|8][0-9]{9}$/;
    var tel = /^\d{3,4}-?\d{7,9}(,|，\d{3,4}-?\d{7,9}){0,2}$/;
    return this.optional(element) || (tel.test(value) || mobile.test(value));
}, "请正确填写您的联系电话");

//中文验证
jQuery.validator.addMethod("isChinese", function(value, element) {
    cname = $.trim(value);
    var chinese = /^[\u4e00-\u9fa5]+$/ ;
    return this.optional(element) || (chinese.test(cname));
}, "姓名必须是中文！");

// 身份证号码验证
jQuery.validator.addMethod("isIdCardNo", function(value, element) {
    return this.optional(element) || isIdCode(value);
},"请正确输入您的身份证号码");

//密码格式验证
jQuery.validator.addMethod("isPassword",function(value , element){
    var pass = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[a-zA-Z\d]{6,20}$/;
    return this.optional(element) || (pass.test(value));
},"密码长度必须大于6位，并且必须包含大小写字母和数字");

//验证银行卡号
jQuery.validator.addMethod("isBankCode",function(value , element){
    var bank =  /^[0-9]{19}$/;
    return this.optional(element) || (bank.test(value));
},"银行卡号必须是19位的数字");

//验证文件上传大小
jQuery.validator.addMethod("checkPicSize", function(value,element) {
    var fileSize=element.files[0].size;
    var maxSize = 1024*1024;
    if(fileSize > maxSize){
        return false;
    }else{
        return true;
    }
}, "请上传大小在1M以下的图片");

function isValidStr(str){
    for (var i = 0; i < str.length; i++)
    {
        if (str.charCodeAt(i) < 127 && !str.substr(i,1).match(/^\w+$/ig))
        {
            return false;
        }
    }
    return true;
}
//简单验证身份证格式
function isIdCode(CodeStr){
    var code = /^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x)|^\d{14}(\d|X|x))$/;
    return code.test(CodeStr);
}
