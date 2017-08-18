
define(function (require,exports,module) {

    require("jq")  

    var vm = avalon.define({
        $id: 'custom',
        init: function () {
             
        }
        ,
        editmodel:{
            accountid:"",
            accountloginname:"",
            accountname:"",
            accountphone:"",
            accountrole:[false,false,false,false]
        }
        ,
        delmodel:{
            accountid:"",
            accountobj:null
        }
        ,
        dellinkmodel:{
            linkid:"",
            linkobj:null
        }
        ,
        addaccount:function(){
            require("module/common/hc.popup.jquery")
            $("#account-add").hcPopup({'width':'550'})
        }
        ,
        editaccount:function(id){
            require("module/common/hc.popup.jquery")
            $.ajax({
                    url: 'getmodelbyid', //url为根据id获取用户信息的请求路径
                    type: "post",
                    dataType: "json",
                    data: {
                        accountid : id //
                    },
                    beforeSend: function () {

                    }
                    ,
                    success: function (data) {
                        
                        var _error_code = data.error_code;
                        var _error_msg = data.error_msg;
                        //假定 _error_code 值
                        //case 0 获取失败
                        //case 1 获取成功 - 赋值 - vm.editmodel
                        
                        if (_error_code == 0 ) {
                            alert('服务器异常')
                        } 
                        else if (_error_code == 1) {
                            //data 为 用户信息的json格式
                            //data中的属性可自行定义
                            //即搜索出来的字段名称
                            vm.editmodel.accountid        = data.accountid 
                            vm.editmodel.accountloginname = data.accountloginname 
                            vm.editmodel.accountname      = data.accountname
                            vm.editmodel.accountphone     = data.accountphone
                            vm.editmodel.accountrole      = data.accountrole
                        }
                        
                    }
                    ,
                    error:function(){
                        //下面代码是测试用的
                        //在实际环境中请删除
                        //数据模拟 真实环境请用success中的方式
                        vm.editmodel.accountid        = 222
                        vm.editmodel.accountloginname = '隔壁老王' 
                        vm.editmodel.accountname      = '王大污'
                        vm.editmodel.accountphone     = '18962196956'
                        vm.editmodel.accountrole      = [true,true,true,false]
                    }
                })
 
            $("#account-modify").hcPopup({'width':'550'})
        }
        ,
        delaccount:function(id){
            require("module/common/hc.popup.jquery")
            $("#account-del").hcPopup({'width':'400'})
            //配置删除对象vm.delmodel 在dodelaccount中用到
            vm.delmodel.accountid  = id //需要删除的id
            vm.delmodel.accountobj = $(this).parents('tr').eq(0) //删除操作成功后需要从dom中删除的行
            
        }
        ,
        isempty:function(obj){
            return $.trim(obj.val()) == ""
        }
        ,
        errorshowhide:function(obj){
            obj.show(function(){
                setTimeout(function(){
                    obj.fadeOut(300)
                },3000)
            })
        }
        ,
        valiteForm:function(_form,callback){
            var _flag      = true
            var _loginname = _form.find("input[name='account-login-name']")
            var _name      = _form.find("input[name='account-name']")
            var _phone     = _form.find("input[name='account-phone']")
            var _role      = _form.find("input[name='account-role']")
            var _roleleng  = _form.find("input[name='account-role']:checked")
            var _pwd       = _form.find("input[name='account-pwd']")
            var _pwd2      = _form.find("input[name='account-pwd-2']")
            if (vm.isempty(_loginname)) {
                _flag = false
                vm.errorshowhide(_loginname.next())
            }
            else if (vm.isempty(_name)) {
                _flag = false
                vm.errorshowhide(_name.next())
            }
            else if (vm.isempty(_phone)) {
                _flag = false
                vm.errorshowhide(_phone.next())
            }
            else if (_roleleng.length == 0) {
                _flag = false
                vm.errorshowhide(_role.parents("td").eq(0).find(".error-div"))
            }
            if (callback) {
                if (vm.isempty(_pwd)) {
                    _flag = false
                    vm.errorshowhide(_pwd.next())
                }
                else if (vm.isempty(_pwd2)) {
                    _flag = false
                    vm.errorshowhide(_pwd2.next())
                }
                else if (_pwd.val() != _pwd2.val()) {
                    _flag = false
                    vm.errorshowhide(_pwd2.next())
                }
                else{
                    callback()
                }
            }else{
                //修改的时候 没有输入密码的时候不验证
                if (!vm.isempty(_pwd) && _pwd.val() != _pwd2.val()) {
                    _flag = false
                    vm.errorshowhide(_pwd2.next())
                }
            }
            
            if (_flag) {
                _form.submit()
            }
        }
        ,
        doaddaccount:function(){
            //require("module/vendor/jquery.form")
            vm.valiteForm($("form[name='addaccount']"),function(){})
        }
        ,
        doeditaccount:function(){
            vm.valiteForm($("form[name='editaccount']"),null)
        }
        ,
        dodelaccount:function(){
            var _this = $(this)
            $.ajax({
                    url: 'delmodelbyid', //
                    type: "post",
                    dataType: "json",
                    data: {
                        accountid : vm.delmodel.accountid  //
                    },
                    beforeSend: function () {

                    }
                    ,
                    success: function (data) {
                        
                        var _error_code = data.error_code;
                        var _error_msg = data.error_msg;
                        //假定 _error_code 值
                        //case 0 删除失败
                        //case 1 删除成功 
                        
                        if (_error_code == 0 ) {
                            alert('服务器异常')
                        } 
                        else if (_error_code == 1) {
                            _this.next().click() //隐藏对话框
                            vm.delmodel.accountid  = 0
                            var _tr = vm.delmodel.accountobj  
                            _tr.fadeOut(300,function(){
                                _tr.remove()
                            }) 
                        }
                        
                    }
                    ,
                    error:function(){
                        //下面代码是测试用的
                        //在实际环境中请删除
                        _this.next().click() //隐藏对话框
                        vm.delmodel.accountid  = 0
                        var _tr = vm.delmodel.accountobj  
                        _tr.fadeOut(300,function(){
                            _tr.remove()
                        })
                    }
                })
        }
        //银行账户信息
        ,
        isCardNo:function (card)  
        {          
           var _flag = true
           var reg = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;  
           if(reg.test(card) === false)  
               _flag =  false  
           return _flag
        }  
        ,
        setImagePreview:function (obj,preview) {

            var docObj = obj
            var imgObjPreview = preview
            
            if(docObj.files &&  docObj.files[0])
            {
                //火狐下，直接设img属性
                //imgObjPreview.style.display = 'block'
                //imgObjPreview.style.width = '130px'
                //imgObjPreview.style.height = '75px'                   
                //imgObjPreview.src = docObj.files[0].getAsDataURL();
                //火狐7以上版本不能用上面的getAsDataURL()方式获取，需要一下方式  
                imgObjPreview.src = window.URL.createObjectURL(docObj.files[0])

            }else{
                    //IE下，使用滤镜
                    docObj.select()
                    var imgSrc = document.selection.createRange().text
                    var localImagId = document.getElementById("localImag")
                    //必须设置初始大小
                    //localImagId.style.width = "300px"
                    //localImagId.style.height = "120px"
                    //图片异常的捕捉，防止用户修改后缀来伪造图片
                    try{
                            localImagId.style.filter="progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale)"
                            localImagId.filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = imgSrc
                    }catch(e){
                            alert("您上传的图片格式不正确，请重新选择!");
                            return false;
                    }
                    imgObjPreview.style.display = 'none'
                    document.selection.empty()
            }
            return true
        }
        ,
        hideComplate:function(code){
            $(this).next("div").addClass("hide").hide()
            var _error = $(this).next("div").next('p').find("span")
            if (code == 'numcard') {

                if (!vm.isCardNo($(this).val())) {
                    _error.removeClass('hide').show()
                    setTimeout(function(){
                        _error.addClass("hide").hide()
                    },3000)
                }

            }
            else if (code=='card') {

            }
        }
        ,
        showNumComplate:function(){
            var _this = $(this)
            var _next =_this.next("div")
            var _val = _this.val()
            if (_val.indexOf(" ") > 0) {
                _this.val(_val.replace(" ",""))
            }
            var _html 
            if(_val.length > 6 && _val.length < 14) {
                _html = _val.slice(0, 6) +" " +_val.slice(6)
            }
            else if( _val.length >= 14) {
                _html = _val.slice(0, 6) + " " + _val.slice(6,14) + " " +_val.slice(14)
            }else{
                _html = _val
            }
            _next.removeClass("hide").show().html(_html)
        }
        ,
        showCardComplate:function(){
            var _this = $(this)
            var _next =_this.next("div")
            var _val = _this.val()
            if (_val.indexOf(" ") > 0) {
                _this.val(_val.replace(" ",""))
            }
            var _html 
            if(_val.length > 4 && _val.length < 8) {
                _html = _val.slice(0, 4) +" " +_val.slice(4)
            }
            else if( _val.length >= 8 && _val.length < 12) {
                _html = _val.slice(0, 4) + " " + _val.slice(4,8) + " " +_val.slice(8,12)
            }
            else if(_val.length >= 12) {
                _html = _val.slice(0, 4) + " " + _val.slice(4,8) + " " +_val.slice(8,12) + " " +_val.slice(12)
            }
            else{
                _html = _val
            }
            _next.removeClass("hide").show().html(_html)
        }
        ,
        showHideError:function(obj){
            obj.removeClass('hide')
            setTimeout(function(){
                obj.addClass('hide')
            },3000)
        }
        ,
        checkBankCard:function (account)
        {
            var flag = true
            var reg = /^\d{19}$/g;  
            if(!reg.test(account) )  flag = false
            else flag = true
            return flag
        }
        ,
        initProvince:function(){
            $(this).next().toggle()
        }
        ,
        selectProvince:function(id){
             var $this = $(this)
             $this.parents(".dropdown-menu").find("input[type='hidden']").eq(0).val($this.text())
             $this.addClass("select-province").siblings().removeClass("select-province")
             $this.parent().prev().find("span").eq(0).removeClass("cur-tab").end().eq(1).addClass("cur-tab")
             $this.parent().hide().next().show()
             //$this.parent().next().find("dd").removeClass("select-city")
             vm.citylist=[]
             $.getJSON("/getcityjosn/"+id+"/",function(data){
                vm.citylist = data
             })

        }
        ,
        selectCity:function(){
             var $this = $(this)
             //$this.addClass("select-city").siblings().removeClass("select-city")
             $this.parents(".dropdown-menu").find("input[type='hidden']").eq(1).val($this.text())
             $(".area-tab-div").hide().prev().find(".dropdown-label span").text(
                $this.parents(".dropdown-menu").find("input[type='hidden']").eq(0).val() + 
                $this.parents(".dropdown-menu").find("input[type='hidden']").eq(1).val()
             )
             $this.parent().hide().prev().show()
             $this.parent().prev().prev().find("span").eq(1).removeClass("cur-tab").end().eq(0).addClass("cur-tab")
                      
        }
        ,
        upload:function(){
             var $this = $(this)
             $this.next().click()
        }
        ,
        change:function(){
             var $this = $(this)
             var _val = $this.val()
             $this.next().val($this.val())
             //$this.prev().attr("src",$this.val())
        }
        ,
        changeAndPreview:function(obj){
             var $this = $(this)
             var _val = $this.val()
             $this.next().val($this.val())
             vm.setImagePreview(obj,$(obj).prev()[0])
             //$this.prev().attr("src",$this.val())
        }
        ,
        SubmitCardInfo:function(){
            
            var _this = $(this)
            var _flag = true
            var _address = $("input[name='address']")
            var _province = $("input[name='province']")
            var _city = $("input[name='city']")
            var _userCard = $("input[name='userCard']")

            if ($.trim(_province.val())=="" ) {
                _flag = false
                var _inputerror_1 = _province.parents(".form-txt").eq(0).find(".inputerror").eq(0)
                vm.showHideError(_inputerror_1)
            }
            else if ( $.trim(_city.val())=="") {
                _flag = false
                var _inputerror_2 = _city.parents(".form-txt").eq(0).find(".inputerror").eq(0)
                vm.showHideError(_inputerror_2)
            }
            else if ( $.trim(_address.val())=="") {
                _flag = false
                var _inputerror = _address.parents(".form-txt").eq(0).find(".inputerror").eq(1)
                vm.showHideError(_inputerror)
            }
            else if ($.trim(_userCard.val())=="" ) {
                _flag = false
                var _inputerror = _userCard.parent().find(".inputerror").eq(1)
                vm.showHideError(_inputerror) 
            }
            else if (!vm.checkBankCard(_userCard.val())) {
                _flag = false
                var _inputerror = _userCard.parent().find(".inputerror").eq(0)
                vm.showHideError(_inputerror) 
            }
           
            $.each($("input[type='file']"),function(index,item){
                if ($(item).val()=="") {
                    $(item).next().next().removeClass('hide').show()
                    vm.showHideError($(item).next().next())
                    _flag = false
                }
            })

            if (_flag) {

                
                var options = {

                    success: function (data) {
                        _this.addClass('oksure').text("\u7b49\u5f85\u5ba1\u6838").attr("disabled","disabled").next().hide()
                    }
                    ,
                    beforeSubmit:function(){

                        _this.addClass('oksure').text("\u7b49\u5f85\u5ba1\u6838").attr("disabled","disabled").next().hide()
                    }
                    ,
                    clearForm:true
                }
                // ajaxForm 
                bankCardForm.ajaxForm(options) 
                // ajaxSubmit
                bankCardForm.ajaxSubmit(options) 
            }

        }
        ,
        //银行账户信息end
        SendCode: function (phone) {
            if (vm.isClick) {
                return
            } 
            var _this = $(this)//发送按钮本身 
            
            $.getJSON("http://www.hwache.cn/sendcode/"+ phone, function (data) {
                var _error_code = data.error_code
                var _error_msg = data.error_msg
                //console.log(data)
                if (_error_code == 1) {
                    vm.code = _error_msg
                    return false
                }
                var _stxt = _this.attr("data-s")
                var _curtxt = _this.attr("data-send")
                vm.isClick = true
                var _timeTMP = setInterval(function () {
                  
                    if (_$time == 0) {
                        _this.text(_stxt)
                        vm.isClick = false
                        _$time = 59
                        clearInterval(_timeTMP)
                    } else {
                        _this.text(_curtxt.replace("$1", _$time));
                        _$time--;
                    }
                }, 1000)
            })
            
            
        }
        ,
        showerror:function(){
            $("#showerror").removeClass('hide').show()
            setTimeout(function(){
                 $("#showerror").addClass('hide').hide()
            },3000) 
        }
        ,
        SendEmail: function () {
             var _form = $("form[name='emailform']")
             var _code = $("input[name='code']")
             if ($.trim(_code.val())!="") {
                $.post("/getValiteCode/", function (data) {
                    if (data == _code.val()) {
                        //发送邮件
                    } else{
                        vm.showerror()
                        _code.focus()
                    }
                })
             }else{
                vm.showerror()
             }
        }
        ,
        modifyPhone:function(){
            var _phone = $("input[name='phone']")
            var _code = $("input[name='phonecode']")
            var telReg = !!_phone.val().match(/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/)
            if ($.trim(_phone.val())=="" || telReg==false){
                _phone.focus().next().removeClass('hide').show()
                 setTimeout(function(){
                   _phone.next().addClass('hide').hide()
                 },3000) 
            }
           
            else{

                if ($.trim(_code.val())!="") {
                    if (_code.val() == vm.code) {
                        //验证通过
                    }else{
                        vm.showerror()
                    }
                }else{
                    vm.showerror()
                }

            }
        }
        ,
        GetCode:function(){
            var _code = $("input[name='phonecode']")
            if ($.trim(_code.val())!="") {
               vm.SendCode(_code.val())
            }else{
                vm.showerror()
            }
            
        }
        ,
        ValiteEmail: function () {
             var _form = $("form[name='emailform']")
             var _code = $("input[name='code']")
             var _email = $("input[name='email']")
             
             if ($.trim(_email.val())=="" || !vm.isEmail(_email.val())){
                 _email.focus().next().removeClass('hide').show()
                 setTimeout(function(){
                   _email.next().addClass('hide').hide()
                 },3000) 
             }
             else if ($.trim(_code.val())=="") {
                vm.showerror()
                _code.focus()
             }else{
                
                $.post("/getValiteCode/", function (data) {
                    if (data == _code.val()) {
                        //发送邮件
                    } else{
                        vm.showerror()
                        _code.focus()
                    }
                })
             }
        }
        //edit custom info
        ,
        addlinkmethod:function(){
            require("module/common/hc.popup.jquery")
            $("#account-add").hcPopup({'width':'400'})
        }
         
        ,
        dellink:function(id){
            require("module/common/hc.popup.jquery")
            $("#account-del").hcPopup({'width':'400'})
            //配置删除对象vm.delmodel 在dodelaccount中用到
            vm.dellinkmodel.linkid  = id //需要删除的id
            vm.dellinkmodel.linkobj = $(this).parents('tr').eq(0) //删除操作成功后需要从dom中删除的行
            
        }
        ,
        dodellink:function(){
            var _this = $(this)
            $.ajax({
                    url: 'dellinkbyid', //
                    type: "post",
                    dataType: "json",
                    data: {
                        linkid : vm.dellinkmodel.linkid  //
                    },
                    beforeSend: function () {

                    }
                    ,
                    success: function (data) {
                        
                        var _error_code = data.error_code;
                        var _error_msg = data.error_msg;
                        //假定 _error_code 值
                        //case 0 删除失败
                        //case 1 删除成功 
                        
                        if (_error_code == 0 ) {
                            alert('服务器异常')
                        } 
                        else if (_error_code == 1) {
                            _this.next().click() //隐藏对话框
                            vm.dellinkmodel.linkid  = 0
                            var _tr = vm.dellinkmodel.linkobj  
                            _tr.fadeOut(300,function(){
                                _tr.remove()
                            }) 
                        }
                        
                    }
                    ,
                    error:function(){
                        //下面代码是测试用的
                        //在实际环境中请删除
                        _this.next().click() //隐藏对话框
                        vm.dellinkmodel.linkid  = 0
                        var _tr = vm.dellinkmodel.linkobj  
                        _tr.fadeOut(300,function(){
                            _tr.remove()
                        })
                    }
                })
        }
        ,
        doaddlink:function(){
            var _form  = $("form[name='addlink']")
            var _flag  = true
            var _name  = _form.find("input[name='link-name']")
            var _phone = _form.find("input[name='link-phone']")
          
            if (vm.isempty(_name)) {
                _flag = false
                vm.errorshowhide(_name.next())
            }
            else if (vm.isempty(_phone)) {
                _flag = false
                vm.errorshowhide(_phone.next())
            }
            if (_flag) {
                _form.submit()
            }
        }
        ,
        edituserinfosub:function(){
            var _form  = $("form[name='user-info-edit']")
            var _flag  = true
            if (_flag) {
                _form.submit()
            }
        }
        //edit custom info end
        ,
        valitepwdmodify:function(){
            var _form  = $("form[name='pwdform']")
            var _flag  = true
            var _code  = _form.find("input[name='ipt-code']")
            var _answ  = _form.find("input[name='ipt-answer']")
            var _card  = _form.find("input[name='ipt-num-card']")

            if (vm.isempty(_code)) {
                _flag = false
                vm.errorshowhide(_code.next().next().next())
            }
            else if (vm.isempty(_answ)) {
                _flag = false
                vm.errorshowhide(_answ.next())
            }
            else if (vm.isempty(_card)) {
                _flag = false
                vm.errorshowhide(_card.next())
            }

            if (_flag) {
                $.post("/getValiteCode/", function (data) {
                    if (data == _code.val()) {
                        _flag = true
                    } else{
                        _flag = false
                        vm.errorshowhide(_card.next())
                    }
                })
            }

            if (_flag) {
                _form.submit()
            }
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
                vm.setHight(0, "span")
            } else if (_normal) {
                //满足强强度必定满足中强度
                //所以在中强度中再次判断是否满足高强度
                if (_max) {
                    vm.setHight(2, "span")
                } else {
                    vm.setHight(1, "span")
                }
            }
            else{

            }

        }
        ,
        setHight: function ( index, tag) {
            var _strong = $(".pwd-strong");
            _strong.find(tag).removeClass("pwdcur").eq(index).addClass("pwdcur");
        }
        ,
        SetPwd: function () {
            var _pwd = $("input[name='pwd']")
            var _pwd2 = $("input[name='pwd2']")
            var _flag = true
            if ($.trim(_pwd.val()) == "") {
                _pwd.focus().next().removeClass("hide")
                _flag = false
                setTimeout(function(){
                    _pwd.focus().next().addClass("hide")
                },3000)
            }
            if ($.trim(_pwd.val()) != "") {
                var _pval = _pwd.val()
                //判断密码长度是否在6~20之间
                if (_pval.length < 6 || _pval.length > 20) {
                    _flag = false
                    _pwd.focus().next().removeClass("hide")
                    $(".pwd-strong").hide()
                } 
                else if ($.trim(_pwd.val()) != $.trim(_pwd2.val())) {
                    _pwd2.focus().next().removeClass("hide")
                    _flag = false
                    setTimeout(function(){
                            _pwd2.focus().next().addClass("hide")
                        },3000)
                    }
            }
            
            //通过填写验证
            if (_flag) {
          
                try {
                    $.post("/user/set/pwd/", function (data) {
                        if (data == 1) {
                            window.location.href = "reg-phone-sucess.html";//验证成功，跳转页面
                        } 
                    })
                } catch (e) {
                    alert('服务器异常！');
                }
            }


        }
        ,
        selectEvent:function(){
            $(this).parent().find("li").removeClass("active").end().end().addClass("active").parent().find("input[type='hidden']").val($(this).text()).parent().prev().find(".dropdown-label span").text($(this).text())
        }
        ,
        SubAddDealersForm:function(){
           //hfbrand  province  city hfdealers txt-dealers-shot-name
           var _from      = $("form[name='add-dealers-form']")
           var _brand     = _from.find("input[name='hfbrand']")
           var _province  = _from.find("input[name='province']")
           var _city      = _from.find("input[name='city']")
           var _dealers   = _from.find("input[name='hfdealers']")
           var _shotname  = _from.find("input[name='txt-dealers-shot-name']")
           var _txtcode   = _from.find("input[name='txtcode']")
           var _reg = /^\d{18}\w+$/g //选填 统一社会信用代码由18位数字+至少一位字母
           if (vm.isempty(_brand)) {
                vm.errorshowhide(_brand.parents('.btn-group').next())
           }
           else if (vm.isempty(_province)) {
                vm.errorshowhide(_province.parents('.btn-group').next())
           }
           else if (vm.isempty(_city)) {
                vm.errorshowhide(_city.parents('.btn-group').next().next())
           }
           else if (vm.isempty(_dealers)) {
                vm.errorshowhide(_dealers.parents('.btn-group').next())
           }
           else if (vm.isempty(_shotname)) {
                vm.errorshowhide(_shotname.next())
           }
           else if (!vm.isChineseChar(_shotname.val())) {
                vm.errorshowhide(_shotname.next().next())
           }
           else if(!vm.isempty(_txtcode) && !_reg.test(_txtcode.val())){
              vm.errorshowhide(_txtcode.next())
           }
           else{
                _form.submit()
           }

        }
        ,
        noFandDelears:function(){
            require("module/common/hc.popup.jquery")
            $("#noFandDelearsWin").hcPopup({'width':'450'})
        }
        ,
        tellme:function(){
             var _form = $("form[name='no-dealers-form']")
             var _brand = _form.find(".custom-txtbrand")
             var _cus   = _form.find("textarea")
             if (vm.isempty(_brand)) {
                vm.errorshowhide(_brand.next())
             }
             else if (vm.isempty(_cus)) {
                vm.errorshowhide(_cus.next())
             }
             else{
                _form.submit()
             }
        }
        ,
        isChineseChar: function (str){   
           var reg = /[\u4E00-\u9FA5]{6}/g;
           return reg.test(str);
        }
        ,
        isPhoneNo:function(phone) { 
            var pattern = /^1[34578]\d{9}$/
            return pattern.test(phone)
        }
        ,
        addServiceSpecialist:function(){
            require("module/common/hc.popup.jquery")
            $("#addServiceSpecialist").hcPopup({'width':'450'})
        }
        ,
        doAddServiceSpecialist:function(){
            require("vendor/jquery.form")
            var options = {
                type: 'post',
                resetForm:true,
                success: function() {
                   //do
                   $("#addServiceSpecialist").hide(function(){
                     setTimeout(function(){
                         window.location.reload()
                     })
                   })
                } 
            } 
            var _form  = $("form[name='addServiceSpecialistForm']")
            var _name  = _form.find("input[name='specialist-name']")
            var _phone = _form.find("input[name='specialist-phone']")
            var _tel   = _form.find("input[name='specialist-tel']")
            if (vm.isempty(_name)) {
                vm.errorshowhide(_name.next())
            }
            else if (vm.isempty(_phone) ) {
                vm.errorshowhide(_phone.next())
            }
            else if (!vm.isPhoneNo(_phone.val())) {
                vm.errorshowhide(_phone.next())
            }
            else if (!vm.isempty(_tel) && isNaN(_tel.val())) {
                vm.errorshowhide(_tel.next())
            }
            else{
                _form.ajaxForm(options).ajaxSubmit()
            }
            console.log(!vm.isempty(_tel))
            console.log(!isNaN(_tel.val()))
        }
        ,
        editServiceSpecialist:function(id){
            require("module/common/hc.popup.jquery")
            $("#editServiceSpecialist").hcPopup({'width':'450'})
            $.ajax({
                 type: "GET",
                 url: "getServiceSpecialistModel",
                 data: {
                    id:id
                 },
                 dataType: "json",
                 success: function(data){
                    //data中的字段名 自己定义
                    //如 data.specialist_name
                    vm.specialist.name   = data.name
                    vm.specialist.phone  = data.phone
                    vm.specialist.tel    = data.tel
                    vm.specialist.remark = data.remark
                    //textarea 赋值不了。。只能这么写
                    $("#editServiceSpecialist textarea").html(data.remark)
                 }
            })
        }
        ,
        doEditServiceSpecialist:function(){
            require("vendor/jquery.form")
            var options = {
                type: 'post',
                success: function() {
                   //do
                   $("#editServiceSpecialist").hide(function(){
                     setTimeout(function(){
                         window.location.reload()
                     })
                   })
                } 
            } 
            var _form  = $("form[name='editServiceSpecialistForm']")
            var _name  = _form.find("input[name='specialist-name']")
            var _phone = _form.find("input[name='specialist-phone']")
            var _tel   = _form.find("input[name='specialist-tel']")
            if (vm.isempty(_name)) {
                vm.errorshowhide(_name.next())
            }
            else if (!vm.isempty(_phone) && !vm.isPhoneNo(_phone.val())) {
                vm.errorshowhide(_phone.next())
            }
            else if (!vm.isempty(_tel) && !vm.isPhoneNo(_tel.val())) {
                vm.errorshowhide(_tel.next())
            }
            else{
                _form.ajaxForm(options).ajaxSubmit()
            }

        }
        ,
        viewServiceSpecialist:function(id){
            require("module/common/hc.popup.jquery")
            $("#viewServiceSpecialist").hcPopup({'width':'450'})
            $.ajax({
                 type: "GET",
                 url: "getServiceSpecialistModel",
                 data: {
                    id:id
                 },
                 dataType: "json",
                 success: function(data){
                    //data中的字段名 自己定义
                    //如 data.specialist_name
                    vm.specialist.name   = data.name
                    vm.specialist.phone  = data.phone
                    vm.specialist.tel    = data.tel
                    vm.specialist.remark = data.remark
                 }
            })
        }
        ,
        delServiceSpecialist:function(id){
            require("module/common/hc.popup.jquery")
            $("#delServiceSpecialist").hcPopup({'width':'450'})
            vm.specialist.id = id
            vm.specialist.model = $(this)
        }
        ,
        doDelServiceSpecialist:function(){
            $.ajax({
                 type: "GET",
                 url: "delServiceSpecialistModel",
                 data: {
                    id:vm.specialist.id  
                 },
                 dataType: "json",
                 success: function(data){
                    $("#delServiceSpecialist").hide()
                    vm.specialist.model.parents("tr").eq(0).fadeOut(500)
                 }
            })
            //真实环境请删除下面两行
            $("#delServiceSpecialist").hide()
            vm.specialist.model.parents("tr").eq(0).fadeOut(500)
        }
        ,
        specialist:{
            id:1, //需要数据交互的ID
            name:"张三",
            phone:"13826517621",
            tel:"13826513462",
            remark:"QQ 12345382988",
            model:null //点击事件对应的点击对象
        }
        ,
        addInsuranceCompany:function(){
            var _tbl = $("#insuranceCompanyList")
            //append的内容写在#insurance-company-tmpl的js模板中(底部)
            //$.clone 没法复制avalon中的事件绑定机制
            //#insurance-company-tmpl 必须写在avalon的作用域范围外
            //不然avalon.scan方法会失败(没有任何事件绑定)
            _tbl.removeClass('hide').show().find("tbody").append(
                $("#insurance-company-tmpl").html()
                )
                avalon.scan(document.getElementById('insuranceCompanyList'),avalon.vmodels["custom"])
        }
        ,
        initClaimsScope:function(id,param){
            //param参数是个数组
            //例如[1,0] 相当于 [true,false]
            //理赔范围 只有本地和异地
            //根据参数中的1和0判断复选框的选中与不选中
            vm.insuranceCompany.saveid = id //用于保存
            var _td     = $(this).parents('td').eq(0).next()
            var _inputs = _td.find("input")
            _td.find(".hide").removeClass("hide")
            _td.next().find(".hide").removeClass("hide")
            //绑定理赔范围
            $.each(param,function(index,item){
                var _input  = _inputs.eq(index)
                var _tip    = _td.find('.span-select-tip')
                var _parent = _input.parent()
                _input.prop("checked",item)
                if (index == 1 && item == 0) {
                    //
                    _tip.fadeIn()
                    setTimeout(function(){
                        _tip.hide()
                    },3000)
                    //
                    _parent.bind("mouseover",function(){
                        _tip.fadeIn()
                    }).bind("mouseout",function(){
                        _tip.hide()
                    })
                }else{
                    _tip.hide()
                    _parent.unbind('mouseover').unbind('mouseout')
                }
            })

        }
        ,
        insuranceCompany:{
            saveid:0,
            id:0,
            model:null
        }
        ,
        delInsuranceCompany:function(id){
            require("module/common/hc.popup.jquery")
            $("#delInsuranceCompany").hcPopup({'width':'450'})
            vm.insuranceCompany.id    = id
            vm.insuranceCompany.model = $(this)
        }
        ,
        doDelInsuranceCompany:function(){
            //if (vm.insuranceCompany.id == 0) return
            $.ajax({
                 type: "GET",
                 url: "doDelInsuranceCompany",
                 data: {
                    id:vm.insuranceCompany.id  
                 },
                 dataType: "json",
                 success: function(data){
                    $("#delInsuranceCompany").hide()
                    vm.insuranceCompany.model.parents("tr").eq(0).fadeOut(500)
                 }
            })
            //真实环境请删除下面两行
            $("#delInsuranceCompany").hide()
            vm.insuranceCompany.model.parents("tr").eq(0).fadeOut(500)
        }
        ,
        saveInsuranceCompany:function(){
            //猜想 应该只是保存个id ？？
            var _td  = $(this).parents('tr').eq(0).find("td").eq(0)
            var _input = _td.find("input[type='hidden']")
            if ($.trim(_input.val()) == "") return
            $.ajax({
                 type: "GET",
                 url: "doSaveInsuranceCompany",
                 data: {
                    id:vm.insuranceCompany.saveid,
                    cid:$("#xx").val(),//需要的参数自定义
                    yid:$("#yy").val()
                 },
                 dataType: "json",
                 success: function(data){
                    _td.find('.save-label').html(_td.find("input[type='hidden']").val()).removeClass('hide').prev().hide()
                    _td = _td.next()
                    var _scope = []
                    $.each(_td.find("input[type='checkbox']:checked"),function(){
                        _scope.push($(this).next().html())
                    })
                    _td.find('.save-label').html(_scope.join('、')).removeClass('hide').prev().hide()
                    _td = _td.next()
                    _td.find(".save").hide().next().show().next().hide().next().show()
                 }
            })
            //真实环境请删除下面代码
            _td.find('.save-label').html(_td.find("input[type='hidden']").val()).removeClass('hide').prev().hide()
            _td = _td.next()
            var _scope = []
            $.each(_td.find("input[type='checkbox']:checked"),function(){
                _scope.push($(this).next().html())
            })
            _td.find('.save-label').html(_scope.join('、')).removeClass('hide').prev().hide()
            _td = _td.next()
            _td.find(".save").hide().next().show().next().hide().next().show()

        }
        ,
        modifyInsuranceCompany:function(id){
            
            var _td  = $(this).parents('tr').eq(0).find("td").eq(0)
            _td.find('.save-label').html('').addClass('hide').prev().css("display","inline-block")
            _td = _td.next()
            _td.find('.save-label').html('').addClass('hide').prev().css("display","inline-block")
            _td = _td.next()
            _td.find(".save").removeClass('hide').show().next().hide().next().show().next().hide()
        }
         

    })

    /*var specialist = function(){}
    specialist.prototype.add = function(arg) {
          
    }
    var sp = new specialist()
    sp.add()*/
    function getEvent(){
        return arguments.callee.caller.arguments[0] || window.event
    }

    $('a[data-toggle="collapse"],.panel-heading').click(function(){
        var event = getEvent()
        if (event.target.tagName == "I") {
            return
        }
        var _i = $(this).find("i")
        if (_i.hasClass('fa-sort-up')) {
            _i.removeClass('fa-sort-up').addClass('fa-sort-down')
        }else{
            _i.removeClass('fa-sort-down').addClass('fa-sort-up')
        }
    })

    module.exports = {
        init:function(){
            vm.init()
        }
        ,
        initInsuranceCompany:function(){
            var _tbl = $("#insuranceCompanyList")
            if (_tbl.find("tr").length <= 2) {
                _tbl.addClass('hide')
            }
        }
    }
        
})

   