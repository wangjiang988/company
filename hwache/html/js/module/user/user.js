
define(function (require, exports, module) {
    
    require("vendor/jquery.form");//
    var vm = avalon.define({
        $id: 'user',
        isClick: false,
        init: function () {
            
        }
        ,
        citylist:[
            {'id':125,'name':'苏州'},
            {'id':126,'name':'常熟'},
            {'id':127,'name':'无锡'},
            {'id':128,'name':'太仓'}
        ]
        ,
        displayTm: function () {
            $(this).parent().next().fadeIn(300)
        }
        ,
        hideTm: function () {
            $(this).parent().next().hide()
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
        checkMyName:function(){
            var _this = $(this)
            var _uservalite = $("#uservalite")
                _uservalite.hide()
            if ($.trim(_this.val())=="") {
                _this.next().hide().next().removeClass('hide').show()
            }
            else{
                _this.next().show().next().hide()
                var _nameIsValite = parseInt($("input[name='nameIsValite']").val())
                
                if (_nameIsValite == 0) {
                    _uservalite.removeClass('hide').show()
                }
                else if (_nameIsValite == 1) {
                    
                }
            }
        }
        ,
        isEmail:function(str){
           var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/;
           return reg.test(str);
        }
        ,
        checkMyEmail:function(){
            var _this = $(this)
            var _emailvalite = $("#emailvalite")
                _emailvalite.hide()

            if ($.trim(_this.val())=="") {
                _this.next().hide().next().removeClass('hide').show()
            }
            else if (!vm.isEmail(_this.val())) {
                _this.next().hide().next().removeClass('hide').show()
            }
            else{
                _this.next().show().next().hide()
                var _emailIsValite = parseInt($("input[name='emailIsValite']").val())
                if (_emailIsValite == 0) {
                    _emailvalite.removeClass('hide').show()
                }
                else if (emailIsValite == 1) {
                    
                }
            }
        }
        ,
        checkInfoForm:function(){
            var _txtName = $("input[name='txtName']")
            var _txtEmail = $("input[name='txtEmail']")
            if ($.trim(_txtName.val())=="") {
                _txtName.focus().next().hide().next().removeClass('hide').show()
                setTimeout(function(){
                    _txtName.next().show().next().addClass('hide').hide()
                },3000)
            }
            else if ($.trim(_txtEmail.val())=="") {
                _txtEmail.focus().next().hide().next().removeClass('hide').show()
                setTimeout(function(){
                    _txtEmail.next().show().next().addClass('hide').hide()
                },3000)
            }
            else if (!vm.isEmail(_txtEmail.val())) {
                _txtEmail.next().hide().next().removeClass('hide').show()
            }
            else
                $("form[name='infoform']").submit()
        }
        ,
        code:""
        ,
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
        ,
        showerror:function(){

            $("#showerror").removeClass('hide').show()
            setTimeout(function(){
                 $("#showerror").addClass('hide').hide()
            },3000) 
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
        next:function (){
            var _phone = $("input[name='phonecode']")
            var tel = _phone.val()

            if ($.trim(tel)!="") {
                if (tel == vm.code) {
                    //验证通过
                }else{
                    vm.showerror()
                }
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
        getCodeImg:function(obj){
            obj.src = 'themes/images/user/code.gif?rc=' + Math.random()
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
        isCardNo:function (card)  
        {          
           var _flag = true
           var reg = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;  
           if(reg.test(card) === false)  
               _flag =  false  
           return _flag
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
        setImagePreview:function (obj,preview) {

            var docObj = obj
            var imgObjPreview = preview
            
            if(docObj.files &&  docObj.files[0])
            {
                //火狐下，直接设img属性
                //imgObjPreview.style.display = 'block'
                imgObjPreview.style.width = '130px'
                imgObjPreview.style.height = '75px'                   
                //imgObjPreview.src = docObj.files[0].getAsDataURL();

                //火狐7以上版本不能用上面的getAsDataURL()方式获取，需要一下方式  
                imgObjPreview.src = window.URL.createObjectURL(docObj.files[0])

            }else{
                    //IE下，使用滤镜
                    docObj.select()
                    var imgSrc = document.selection.createRange().text
                    var localImagId = document.getElementById("localImag")
                    //必须设置初始大小
                    localImagId.style.width = "300px"
                    localImagId.style.height = "120px"
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
        SubmitValite:function(){
            var _flag = true
            var _username = $("input[name='username']")
            var _userNumcard = $("input[name='userNumcard']")
            var _address = $("input[name='address']")
            var _province = $("input[name='province']")
            var _city = $("input[name='city']")
            var _userCard = $("input[name='userCard']")

            if ($.trim(_username.val())=="") {
                _flag = false
                var _inputerror = _username.parent().find(".inputerror")
                vm.showHideError(_inputerror)
            }
            else if ($.trim(_userNumcard.val())=="") {
                _flag = false
                var _inputerror = _userNumcard.parent().find(".inputerror")
                vm.showHideError(_inputerror)
            }
            else if ($.trim(_province.val())=="" ) {
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

                var valiteForm = $("form[name='valiteForm']")
                var options = {

                    success: function (data) {

                    }
                    ,
                    beforeSubmit:function(){

                        
                    }
                    ,
                    clearForm:true
                }
                // ajaxForm 
                valiteForm.ajaxForm(options) 
                // ajaxSubmit
                valiteForm.ajaxSubmit(options) 
            }

        }
        ,
        viewImg:function(src){
            $(".viewimg").show().find("img").attr("src",src)
        }
        ,
        closeImg:function(){
            $(".viewimg").fadeOut()
        }
        ,
        addAndEditCardForm:function(){
            $("form[name='bankCardForm']").removeClass('hide')
        }
        ,
        SubmitCardInfo:function(){
            
            var _this = $(this)
            var _flag = true
            var _username = $("input[name='username']")
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
            else if ($.trim(_username.val())=="") {
                _flag = false
                var _inputerror = _username.parent().find(".inputerror")
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
        initOrderTime:function(){
           $(this).next(".dropdown-menu").toggle().find(".dl").toggle()
        }
        ,
        selectOrderTime:function(val){
            $(this).addClass("select-order-time").siblings().removeClass('select-order-time').parent().hide().parent().hide()
            $(this).parent().parent().prev().find(".dropdown-label span").text(val)
            $(this).parent().parent().next().val(val)
        }
        ,
        billing:function(){

            require("module/common/hc.popup.jquery")
            var _flag = true
            var _billingform = $("form[name='billingform']")
            var _hffp        = _billingform.find("input[name='hffp']")
            var _address     = _billingform.find("textarea[name='address']")
            var _receiver    = _billingform.find("input[name='receiver']")
            var _phone       = _billingform.find("input[name='phone']")
            var _invoicetype = _billingform.find("input[name='invoicetype']")
            var _taitou      = _billingform.find("input[name='taitou']")
            
            if (_taitou.attr("valite-input")) {
                if ($.trim(_taitou.val())=="") {
                    var _inputerror = _hffp.parent().next().next().eq(0)
                    vm.showHideError(_inputerror) 
                    _flag = false
                }
            }

            if ($.trim(_hffp.val())=="") {
                var _inputerror = _hffp.parent().next(".inputerror").eq(0)
                vm.showHideError(_inputerror) 
                _flag = false
            }
            
            else if ($.trim(_address.val())=="") {
                var _inputerror = _address.parent().find(".inputerror").eq(0)
                vm.showHideError(_inputerror) 
                _flag = false
            }
            else if ($.trim(_receiver.val())=="") {
                var _inputerror = _receiver.parent().find(".inputerror").eq(0)
                vm.showHideError(_inputerror) 
                _flag = false
            }
            else if ($.trim(_phone.val())=="") {
                var _inputerror = _phone.parent().find(".inputerror").eq(0)
                vm.showHideError(_inputerror) 
                _flag = false
            }

            else if(parseInt(_invoicetype.val()) == 1){
                var _addsn      = _billingform.find("input[name='addsn']")
                var _addaddress = _billingform.find("input[name='addaddress']")
                var _addphone   = _billingform.find("input[name='addphone']")
                var _addbank    = _billingform.find("input[name='addbank']")
                var _addaccount = _billingform.find("input[name='addaccount']")
                if ($.trim(_addsn.val())=="") {
                    var _inputerror = _addsn.parent().find(".inputerror").eq(0)
                    vm.showHideError(_inputerror) 
                    _flag = false
                }
                else if ($.trim(_addaddress.val())=="") {
                    var _inputerror = _addaddress.parent().find(".inputerror").eq(0)
                    vm.showHideError(_inputerror) 
                    _flag = false
                }
                else if ($.trim(_addphone.val())=="") {
                    var _inputerror = _addphone.parent().find(".inputerror").eq(0)
                    vm.showHideError(_inputerror) 
                    _flag = false
                }
                else if ($.trim(_addbank.val())=="") {
                    var _inputerror = _addbank.parent().find(".inputerror").eq(0)
                    vm.showHideError(_inputerror) 
                    _flag = false
                }
                else if ($.trim(_addaccount.val())=="") {
                    var _inputerror = _addaccount.parent().find(".inputerror").eq(0)
                    vm.showHideError(_inputerror) 
                    _flag = false
                }

            }

            if (_flag) {
                //_billingform.submit()
                $("#billing-tip").hcPopup()
            }

        }   
        ,
        showTaitou:function(){
            $(this).parents(".form-txt").find(".edit-long").removeClass('hide').find("input").attr("valite-input",1)
        }
        ,
        hideTaitou:function(){
            $(this).parents(".form-txt").find(".edit-long").addClass('hide')
        }
        ,
        rebilling:function(){
            require("module/common/hc.popup.jquery")
            var _flag = true
            var _billingform = $("form[name='rebillingform']")
            var _reason      = _billingform.find("input[name='reason']")
            var _kuaidi      = _billingform.find("input[name='kuaidi']")
            var _kuaidisn    = _billingform.find("input[name='kuaidisn']")
            var _hffp        = _billingform.find("input[name='hffp']")
            var _address     = _billingform.find("textarea[name='address']")
            var _receiver    = _billingform.find("input[name='receiver']")
            var _phone       = _billingform.find("input[name='phone']")
            var _invoicetype = _billingform.find("input[name='invoicetype']")
            
            var _taitou      = _billingform.find("input[name='taitou']")
            
            if (_taitou.attr("valite-input")) {
                if ($.trim(_taitou.val())=="") {
                    var _inputerror = _hffp.parent().next().next().eq(0)
                    vm.showHideError(_inputerror) 
                    _flag = false
                }
            }
            
            if ($.trim(_reason.val())=="") {
                var _inputerror = _reason.parent().next(".inputerror").eq(0)
                vm.showHideError(_inputerror) 
                _flag = false
            }
            else if ($.trim(_kuaidi.val())=="") {
                var _inputerror = _kuaidi.next(".inputerror").eq(0)
                vm.showHideError(_inputerror) 
                _flag = false
            }
            else if ($.trim(_kuaidisn.val())=="") {
                var _inputerror = _kuaidisn.next(".inputerror").eq(0)
                vm.showHideError(_inputerror) 
                _flag = false
            }
            else if ($.trim(_hffp.val())=="") {
                var _inputerror = _hffp.parent().next(".inputerror").eq(0)
                vm.showHideError(_inputerror) 
                _flag = false
            }
            else if ($.trim(_address.val())=="") {
                var _inputerror = _address.parent().find(".inputerror").eq(0)
                vm.showHideError(_inputerror) 
                _flag = false
            }
            else if ($.trim(_receiver.val())=="") {
                var _inputerror = _receiver.parent().find(".inputerror").eq(0)
                vm.showHideError(_inputerror) 
                _flag = false
            }
            else if ($.trim(_phone.val())=="") {
                var _inputerror = _phone.parent().find(".inputerror").eq(0)
                vm.showHideError(_inputerror) 
                _flag = false
            }
            else if(parseInt(_invoicetype.val()) == 1){
                var _addsn      = _billingform.find("input[name='addsn']")
                var _addaddress = _billingform.find("input[name='addaddress']")
                var _addphone   = _billingform.find("input[name='addphone']")
                var _addbank    = _billingform.find("input[name='addbank']")
                var _addaccount = _billingform.find("input[name='addaccount']")
                if ($.trim(_addsn.val())=="") {
                    var _inputerror = _addsn.parent().find(".inputerror").eq(0)
                    vm.showHideError(_inputerror) 
                    _flag = false
                }
                else if ($.trim(_addaddress.val())=="") {
                    var _inputerror = _addaddress.parent().find(".inputerror").eq(0)
                    vm.showHideError(_inputerror) 
                    _flag = false
                }
                else if ($.trim(_addphone.val())=="") {
                    var _inputerror = _addphone.parent().find(".inputerror").eq(0)
                    vm.showHideError(_inputerror) 
                    _flag = false
                }
                else if ($.trim(_addbank.val())=="") {
                    var _inputerror = _addbank.parent().find(".inputerror").eq(0)
                    vm.showHideError(_inputerror) 
                    _flag = false
                }
                else if ($.trim(_addaccount.val())=="") {
                    var _inputerror = _addaccount.parent().find(".inputerror").eq(0)
                    vm.showHideError(_inputerror) 
                    _flag = false
                }

            }

            if (_flag) {
                //_billingform.submit()
                $("#billing-retip").hcPopup()
            }

        } 
        ,
        submitBilling:function(){
            $("form[name='billingform']").submit()
        }
        ,
        submitreBilling:function(){
            $("form[name='rebillingform']").submit()
        }
        ,
        confirmReceipt:function(){
            var _this = $(this)
            _this.text("已收到").addClass("oksure")
            $.post("/confirmReceipt/",function(data){
                if (data==1) {

                }else{

                }
            })
        }
        ,
        SubmiteFile:function(){

            var _flag        = true
            var _kpform      = $("form[name='kpform']")
            var _guobieCheck = _kpform.find("input[name='guobie']:checked")
            var _guobie      = _kpform.find("input[name='guobie']")
            var _filename    = _kpform.find("input[name='filename']")
            var _province    = _kpform.find("input[name='province']")
            var _city        = _kpform.find("input[name='city']")
            var _caryongtu   = _kpform.find("input[name='caryongtu']")
            var _typetypeC   = _kpform.find("input[name='typetype']:checked")
            var _typetypeQ   = _kpform.find("input[name='typeqiye']:checked")
            var _othertype   = _kpform.find("input[name='othertype']")
            var _hujicity    = _kpform.find("input[name='hujicity']")
            var _addressarea = _kpform.find("input[name='addressarea']")
            
            if (_guobieCheck.length == 0) {
                var _inputerror = _guobie.parents(".form-txt").find(".inputerror")
                vm.showHideError(_inputerror) 
                _flag = false
            }
            else if ($.trim(_filename.val())=="") {
                var _inputerror = _filename.parent().next(".inputerror")
                vm.showHideError(_inputerror) 
                _flag = false
            }
            else if ($.trim(_province.val())=="" || $.trim(_city.val())=="") {
                var _inputerror = _province.parents(".form-txt").find(".inputerror")
                vm.showHideError(_inputerror) 
                _flag = false
            }
            else if ($.trim(_caryongtu.val())=="" ) {
                var _inputerror = _caryongtu.parents(".form-txt").find(".inputerror")
                vm.showHideError(_inputerror) 
                _flag = false
            }
            else if (vm.carMethodIndex == 1 ) {
                if (_typetypeC.length == 0) {
                    var _inputerror = $("#identityCate")
                    vm.showHideError(_inputerror) 
                    _flag = false
                }
            }
            else if (vm.carMethodIndex == 2 ) {
                if (_typetypeQ.length == 0) {
                    var _inputerror = $("#identityCate")
                    vm.showHideError(_inputerror) 
                    _flag = false
                }
            }
            /*else if (_typetypeC.length == 0) {
                var _inputerror = $("#identityCate")
                vm.showHideError(_inputerror) 
                _flag = false
            }*/
            if (_flag) {
                _kpform.submit()
            }

            //carMethodIndex
        }
        ,
        selectIdendityCate:function(index){
            
            var _kpform      = $("form[name='kpform']")
            var _othertype   = _kpform.find("input[name='othertype']")

            if (index == 1) {
                if ($(this).prop("checked")) {
                    _othertype.eq(0).prop("checked",true)
                }else{
                    _othertype.removeAttr('checked')
                    $(".select-city-cur").removeClass('select-city-cur')
                    $(".city-select").find("input[type='hidden']").val("")
                }
            }
        }
        ,
        selectIdendityCateOther:function(index){
            $(this).parents(".form-txt:eq(0)").find("input[type='checkbox']").prop("checked",true)
            var _cityselect = $(".city-select")
            if (index == 1) {
                var _dl = _cityselect.eq(0)
                if (_dl.find(".select-city-cur").length == 0) {
                    _dl.find("dd:eq(0) i").addClass('select-city-cur')
                }
                _dl.find("input[type='hidden']").val(_dl.find(".select-city-cur").prev().text())

            }
            else if (index == 2) {
                var _dl = _cityselect.eq(1)
                if (_dl.find(".select-city-cur").length == 0) {
                    _dl.find("dd:eq(0) i").addClass('select-city-cur')
                }
                _dl.find("input[type='hidden']").val(_dl.find(".select-city-cur").prev().text())

            }
            else if (index == 0) {
                $(".select-city-cur").removeClass('select-city-cur')
                $(".city-select").find("input[type='hidden']").val("")
            }
        }
        ,
        SelectCityType:function(){

            var _this = $(this)
            var _parent = $(this).parent()
            $(this).parents(".form-txt:eq(0)").find("input[type='checkbox']").prop("checked",true)
            
            _this.parent().find("input[type='hidden']").val(_this.find("span").text())
            _this.find("i").addClass('select-city-cur').end().siblings().find("i").removeClass('select-city-cur')
            var _obj = _parent.attr("data-for") 
            $("#"+_obj).prop("checked",true)

        }
        ,
        addSomeFile:function(){
            $("form[name='kpform']").removeClass('hide').fadeIn(200)
        }
        ,
        carMethodIndex:0
        ,
        selectCarMethod:function(index){
           vm.carMethodIndex = index
           $(".box-select").addClass('hide')
           if (index == 1) {
                $(".box-select").eq(0).removeClass('hide')
           }    
           else if (index == 2) {
                $(".box-select").eq(1).removeClass('hide')
           }          
        }
        ,
        showFileView:function(erromsg){
            var _haserror = $(".haserror")
            var _auditerror = $(".audit-error")
            var _boxview = $(".box-view")
            var _btnFileView = $("#btnFileView")
            _btnFileView.text(_btnFileView.attr("data-def")).removeClass('oksure')
            if (erromsg =='sucuss') {
                _auditerror.hide()
                _haserror.hide()
            }
            else if (erromsg =='fail') {
                _auditerror.show()
                _haserror.show()
            }
            else if (erromsg =='on') {
                _auditerror.hide()
                _haserror.hide()
                _btnFileView.text(_btnFileView.attr("data-title")).addClass('oksure')
            }
            _boxview.removeClass('hide').show()
        }
        ,
        selectPayMethod: function () {
            var _this = $(this)
            _this.find("span").addClass("selectpay").end().siblings('li').find("span").removeClass('selectpay')
            $("input[name='paymethod']").val(_this.index())
            $(".showerror").addClass('hide')
        }
        ,
        subPayForm: function () {
            var _this = $(this)
            var _input = $("input[name='payprice']")
            if ($.trim($("input[name='paymethod']").val()) == "") {
                _this.prev().removeClass("hide")
            }
            else if ($.trim(_input.val()) == "") {
                _input.attr("placeholder","请填写金额")
            }else if(isNaN(_input.val())){
                _input.focus()
                $(".inputerror:eq(0)").removeClass('hide').show()
                setTimeout(function(){
                    $(".inputerror:eq(0)").fadeOut(300)
                },3000)
            }else if (parseInt(_input.attr("data-price-max")) < parseInt(_input.val())) {
                _input.focus()
                $(".inputerror:eq(1)").removeClass('hide').show()
                setTimeout(function(){
                    $(".inputerror:eq(1)").fadeOut(300)
                },3000)
            }else{
                _input.attr("placeholder","")
                $("form[name='payform']").submit()
            }

            
        }
        ,
        uploadcount:0
        ,
        uploadForMuliteFileInput:function(){

            var _flag = true
            var pzform = $("form[name='pzform']")
            var _payprice = pzform.find("input[name='payprice']")
            var _inputerror  = _payprice.parent().find(".inputerror:eq(1)")
            if ($.trim(_payprice.val()) != "") {
                    
                if (isNaN(_payprice.val())) {

                    var _inputerror  = _payprice.parent().find(".inputerror:eq(1)")
                    _inputerror.removeClass('hide')
                    setTimeout(function(){
                        _inputerror.addClass('hide')
                    },3000)
                    _flag = false
                }

            }else{
                _inputerror.removeClass('hide')
                setTimeout(function(){
                    _inputerror.addClass('hide')
                },3000)
                _flag = false
            }
            if (_flag) {
                 if (vm.uploadcount < 5) {
                     var $this = $(this)
                     var _fileinputlist = $this.next()
                     var _file = $("<input type='file' class='hide' name='FileUpload' />")
                     _file.bind("change",function(){
                        var _val = _file.val()
                        $(".uploadlist").append("<span class='file-prev'>"+_val.substring(_val.lastIndexOf('\\')+1)+"（￥"+_payprice.val()+"） </span>")
                        vm.uploadcount ++
                     })
                     _fileinputlist.append(_file.click())
                 }else{
                    alert('最多支持上传5个文件')
                 }
            }

        }
        ,
        postpz:function(){

            require("vendor/jquery.form")
            //js 获取不到动态append的file的value值
            //点击上传的时候 不选择文件的时候会出现空value的file控件
            //之后的 '_fileinputlist.length' 验证就失效了
            //暂时先这样吧 之后看看有么有好的办法
            /*$.each($(".fileinputlist").find("input[type='file']"),function(index,item){
                console.log("$(item).val():" + ($(item).val()==''))
                if ($(item).val() && $.trim($(item).val())=="") {
                    $(item).remove()
                }
            })*/

            var _fileinputlist = $(".fileinputlist input")

            if (_fileinputlist.length != 0) {
                
                var pzform = $("form[name='pzform']")
                var _payprice = pzform.find("input[name='payprice']")
                if ($.trim(_payprice.val()) == "") {
                    var _inputerror  = _payprice.parent().find(".inputerror:eq(0)")
                    _inputerror.removeClass('hide')
                    setTimeout(function(){
                        _inputerror.addClass('hide')
                    },3000)
                }
                else{

                    if ($.trim(_payprice.val()) != "") {
                    
                        if (isNaN(_payprice.val())) {

                            var _inputerror  = _payprice.parent().find(".inputerror:eq(1)")
                            _inputerror.removeClass('hide')
                            setTimeout(function(){
                                _inputerror.addClass('hide')
                            },3000)
                            return false
                        }
                    }

                    var options = {
                        success: function (data) {
                            $(".loading6:eq(0)").fadeOut(300)
                            vm.uploadcount = 0
                        }
                        ,
                        beforeSubmit:function(){

                            $(".loading6:eq(0)").fadeIn(300)
                        }
                        ,
                        clearForm:true
                    }
                    // ajaxForm 
                    pzform.ajaxForm(options) 
                    // ajaxSubmit
                    pzform.ajaxSubmit(options) 
                }
               
            }else{
                //没有文件不做任何处理
                $("#fileerror").removeClass('hide')
                setTimeout(function(){
                    $("#fileerror").addClass('hide')
                },3000)
            } 

            
        }
        ,
        selectInvoice:function(isshowaddinfo){
            var _wrapper = $(this).parents("form").eq(0).find(".add-wrapper")
            _wrapper.hide()
            if (isshowaddinfo == 1) {
               _wrapper.show()
            }
            $(this).addClass('fptype-cur').siblings().removeClass('fptype-cur').parent().find("input[type='hidden']").val(isshowaddinfo)
        }
        ,
        viewImg:function(src){
            $(".viewimg").show().find("img").attr("src",src)
        }
        ,
        closeImg:function(){
            $(".viewimg").fadeOut()
        }
        ,
        delImg:function(id){
            $(this).parent().hide()
            $.ajax({
                 type: "post",
                 url: "delImg",
                 data: {
                    id:id
                 },
                 dataType: "json",
                 success: function(data){
                     
                 }
            })
        }
        ,
        selectEvent:function(){
            $(this).parent().find("li").removeClass("active").end().end().addClass("active").parent().find("input[type='hidden']").val($(this).text()).parent().prev().find(".dropdown-label span").text($(this).text())
        }
        ,
        sureRefund:function(){
            $(this).parent().prev().find(".none").removeClass('none')
            $(".yue-content").remove()
            $(".box-border.pay-box").unwrap($(".content-wapper")).addClass('noborder');

        }

        
    })
     
    
})