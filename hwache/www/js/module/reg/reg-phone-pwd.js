define(function (require) {

    require("jq");//
    require("module/reg/reg-common");
   
    var vm = avalon.define({
        $id: 'reg',
        init: function () {

        }
        ,
        setHight: function ( index, tag) {
            var _strong = $(".pwd-strong");
            _strong.find(tag).removeClass("pwdcur").eq(index).addClass("pwdcur");
        }
        ,
        pwdStrong: function () {
            //检测密码强度
            var _pwd = $(this);
            //强|中|弱
            //弱：纯数字，纯字母，纯特殊字符
            //中：字母+数字，字母+特殊字符，数字+特殊字符 
            //强：字母+数字+特殊字符 
            var _max = !!_pwd.val().match(/^(?![a-zA-z]+$)(?!\d+$)(?![!@#$%^&*]+$)(?![a-zA-z\d]+$)(?![a-zA-z!@#$%^&*]+$)(?![\d!@#$%^&*]+$)[a-zA-Z\d!@#$%^&*]+$/);
            var _normal = !!_pwd.val().match(/^(?![a-zA-z]+$)(?!\d+$)(?![!@#$%^&*]+$)[a-zA-Z\d!@#$%^&*]+$/);
            var _less = !!_pwd.val().match(/^(?:\d+|[a-zA-Z]+|[!@#$%^&*]+)$/);
         
            if (_less) {
                vm.setHight(2, "span")
            } else if (_normal) {
                //满足强强度必定满足中强度
                //所以在中强度中再次判断是否满足高强度
                if (_max) {
                    vm.setHight(0, "span")
                } else {
                    vm.setHight(1, "span")
                }
            }
            else{

            }

        }
        ,
        SetPwd: function () {
            var _pwd = $("input[name='pwd']");
            var _pwd2 = $("input[name='pwd2']");
            var _flag = true;
            if ($.trim(_pwd.val()) == "") {
                _pwd.focus().next().removeClass("hide");
                _flag = false;
            }
            if ($.trim(_pwd.val()) != "") {
                var _pval = _pwd.val(); 
                //判断密码长度是否在6~16之间
                if (_pval.length < 6 || _pval.length > 16) {
                    _flag = false;
                    _pwd.focus().next().removeClass("hide");
                } 
                else if ($.trim(_pwd.val()) != $.trim(_pwd2.val())) {
                    _pwd2.focus().next().removeClass("hide");
                    _flag = false;
                }
            }
            
            //通过填写验证
            if (_flag) {
                    $("form[name='pwdform']").submit() 
                
            }


        }
      
    });
    
    vm.init();

});