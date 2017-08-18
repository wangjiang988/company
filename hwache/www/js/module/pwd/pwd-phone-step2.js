define(function (require) {

    require("jq");//
    require("module/reg/reg-common");
    

   
    var vm = avalon.define({
        $id: 'reg',
     
        init: function () {

        } 
        
        ,
        UpdatePwd: function () {
            var _pwd = $("input[name='pwd']");
            var _pwd2 = $("input[name='pwd2']");
            var _flag = true;
            
            if ($.trim(_pwd.val())=="") {
                _pwd.focus().next().removeClass("hide");
                _flag = false;
            } else if ($.trim(_pwd2.val())=="") {
                _pwd2.focus().next().removeClass("hide");
                _flag = false;
            }
           else if ($.trim(_pwd2.val())!=$.trim(_pwd.val())) {
                _pwd2.focus().next().removeClass("hide");
                _flag = false;
            }
            //通过填写验证
            if (_flag) {
                $("form[name='pwdform']").submit()    
               
            }
        }
      
    });
    
    vm.init();

});