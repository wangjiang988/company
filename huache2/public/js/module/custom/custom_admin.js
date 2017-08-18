
define(function (require,exports,module) {

    require("jq") 
    require("module/common/hc.popup.jquery")
    var vm = avalon.define({
        $id: 'custom',
        citylist:[],
        dealerlist:[],
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
        isEmail:function(email){
            var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/; 
            return reg.test(email); 
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
        initProvinceAdv:function(){
            var _brand = $("#sell-brand input[type='hidden']").val().trim()
            $(".btn-group").removeClass('open')
            if (_brand != "") {
                $(this).next().toggle()
            }else{
                $(this).find(".dropdown-label span").html("<span class='red'>请选择销售品牌</span>")
            }
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
              if($("input[name=province_id]").length>0){
                  $("input[name=province_id]").val(id);
              }

        }
        ,
        selectCity:function(city_name){
             var $this = $(this)
             //$this.addClass("select-city").siblings().removeClass("select-city")
             $this.parents(".dropdown-menu").find("input[type='hidden']").eq(1).val($this.text())
             $(".area-tab-div").hide().prev().find(".dropdown-label span").text(
                $this.parents(".dropdown-menu").find("input[type='hidden']").eq(0).val() + 
                $this.parents(".dropdown-menu").find("input[type='hidden']").eq(1).val()
             )
             if($('input[name='+city_name+']').length>0){
                 var city_id = $this.attr('data-id');
                 $('input[name='+city_name+']').val(city_id);
                 $.getJSON("/dealer/getdealerlist/"+city_id+"/",function(data){
                     vm.dealerlist = data
                     //当没有数据的时候的处理方式
                     var _dropdown = $this.parents("tr").next().find(".dropdown-menu")
                     _dropdown.find("li.tac").remove()
                     _dropdown.find("input").val("")
                     if (vm.dealerlist.length == 0) {
                        _dropdown.append('<li class="tac"><a><span class="red">抱歉,没有找到相应的经销商!</span></a></li>')
                     } 
                 })
             }
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
                if ($(item).val()=="" && $(item).prev().attr('src')=='/upload/') {
                    $(item).next().next().removeClass('hide').show()
                    vm.showHideError($(item).next().next())
                    _flag = false
                }
            })
            if (_flag) {

                
                var options = {

                    success: function (data) {
                        _this.addClass('oksure').text("\u7b49\u5f85\u5ba1\u6838").attr("disabled","disabled").next().hide()
                        if(data.error_code==0){
                            alert('数据保存失败');
                        }else{
                            alert('数据保存成功');
                            window.location.reload();
                        }
                    }
                    ,
                    beforeSubmit:function(){

                        _this.addClass('oksure').text("\u7b49\u5f85\u5ba1\u6838").attr("disabled","disabled").next().hide()
                    }
                    ,
                    clearForm:true
                }
                bankCardForm = $("form[name=bankCardForm]")
                bankCardForm.ajaxForm(options) 
                bankCardForm.ajaxSubmit(options)
                 
            }

        }
        ,
        //银行账户信息end
        SendCode: function (phone,type) {
            if (vm.isClick) {
                return
            } 
            var _this = $(this)//发送按钮本身 
            var _this_bk = $(this) //发送按钮本身备用
            $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
              });
            $.ajax({
                    url: '/dealer/sendmobilecode', 
                    type: "post",
                    dataType: "json",
                    data: {
                            phone:phone,
                            type:type
                          },
                    beforeSend: function () {
                        
                    }
                    ,
                    success: function (data) {
                        
                        var _error_code = data.error_code
                        var _error_msg = data.error_msg
                      //console.log(data)
                        if (_error_code == 1) {
                            vm.code = _error_msg
                            return false
                        }
                        if(type==4){
                            var _this = $("input[name='phonecode']").next();//修改新号码时，对象重新赋值
                        }
                        if(_this ==null || _this==''){
                            _this = _this_bk;
                        }
                        var _stxt = _this.attr("data-s")
                        var _curtxt = _this.attr("data-send")
                        vm.isClick = true
                        var _$time = 59;
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
                        
                    }
                    ,
                    error:function(){
                        
                       
                    }
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
        CheckMobileCode: function (method) {
             if(method =='email'){
                 var _type = 5;//修该邮箱 手机验证
                 var _url = '/dealer/changeemail/checkcodebymobile';
             }else if(method =='mobile'){
                 var _type = 3;//修该手机号码验证
                 var _url ='/dealer/changemobile/checkcodebymobile' ;
             }else{
                 vm.showerror();
                 return false;
             }
             var _form = $("form[name='mobileform']")
             var _code = $("input[name='code']")
             if ($.trim(_code.val())!="") {
                 $.ajaxSetup({
                     headers: {
                       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                     }
                 });
                $.ajax({
                     url: _url, 
                     type: "post",
                     dataType: "json",
                     data: {
                            code:_code.val(),
                            type:_type,
                          },
                     success: function (data) {
                         
                         var _error_code = data.error_code
                         var _error_msg = data.error_msg
                         if (_error_code == 1) {
                             if(method == 'email'){
                                 window.location.href = "/dealer/changeemail/input";//验证成功，跳转页面
                             }else if(method == 'mobile'){
                                 window.location.href = "/dealer/changemobile/input";//验证成功，跳转页面
                             }
                             
                         }else{
                             vm.showerror();
                         } 
                         
                     }
                  
                })
             }else{
                vm.showerror()
             }
        }
        ,
        SendEmail: function (email,to) {
            var _form = $("form[name='emailform']")
            var _code = $("input[name='code']")
            if(to==1){//修改手机号码 邮箱验证
                var _url='/dealer/changemobile/checkcodebyemail';
                var _url_success = "/dealer/changemobile/checkemailsuccess";;
                var _url_failure = "/dealer/changemobile/checkemailfailure";
            }else if(to==2){//修改邮箱 邮箱验证
                var _url='/dealer/changeemail/checkcodebyorginemail';
                var _url_success = '';
                var _url_failure = '';
            }else{
                vm.showerror()
                _code.focus()
            }
             
             if ($.trim(_code.val())!="") {
                 $.ajaxSetup({
                     headers: {
                       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                     }
                 });
                $.ajax({
                     url: _url, 
                     type: "post",
                     dataType: "json",
                     data: {
                            code:_code.val(),
                            email:email
                          },
                     success: function (data) {
                         
                         var _error_code = data.error_code
                         var _error_msg = data.error_msg
                         if (_error_code == 1) {
                             window.location.href = _url_success//验证成功，跳转页面
                         }else if(_error_code == 2){
                             window.location.href = _url_failure;//验证成功，跳转页面
                         }else{
                             vm.showerror()
                             _code.focus()
                         } 
                         
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
                    $.ajaxSetup({
                        headers: {
                          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: '/dealer/changemobile/modifymobile', 
                        type: "post",
                        dataType: "json",
                        data: {
                                code:_code.val(),
                                phone:_phone.val(),
                                type:4,
                              },
                        success: function (data) {
                            
                            var _error_code = data.error_code
                            var _error_msg = data.error_msg
                            if (_error_code == 1) {
                                window.location.href = "/dealer/changemobile/success";//验证成功，跳转页面
                            }else{
                             vm.showerror();
                            } 
                            
                        }
                     
                    })
                }else{
                    vm.showerror()
                }

            }
        }
        ,
        GetCode:function(){
            var _phone = $("input[name='phone']")
            if ($.trim(_phone.val())!="") {
               vm.SendCode(_phone.val(),4)
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
                
                 $.ajaxSetup({
                     headers: {
                       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                     }
                 });
                $.ajax({
                     url: '/dealer/changeemail/checkcodebyemail', 
                     type: "post",
                     dataType: "json",
                     data: {
                            code:_code.val(),
                            email:_email.val()
                          },
                     success: function (data) {
                         
                         var _error_code = data.error_code
                         var _error_msg = data.error_msg
                        //return false;
                         if (_error_code == 1) {
                             window.location.href = "/dealer/changeemail/checkemailsuccess";//验证成功，跳转页面
                         }else if(_error_code == 2){
                             window.location.href = "/dealer/changeemail/checkemailfailure";//验证成功，跳转页面
                         }else{
                             vm.showerror()
                             _code.focus()
                         } 
                         
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
            $.ajaxSetup({
                    headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            $.ajax({
                    url: '/dealer/member_info/del-other-contact', //
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
                        if(_error_code==1){
                            alert('其他联系方式删除失败！')
                        }else if(_error_code==0){
                            alert('其他联系方式删除成功！')
                            window.location.reload();
                        }
                        
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
                var options = {
                        success: function (data) {
                            if(data.error_code==0){
                                alert('数据保存失败');
                            }else{
                                alert('数据保存成功');
                                window.location.reload();
                            }
                        }
                    }
                    _form.ajaxForm(options) 
                    _form.ajaxSubmit(options)
            }
        }
        ,
        edituserinfosub:function(){
            var _form  = $("form[name='user-info-edit']")
            var _flag  = true
            if (_flag) {
                var options = {
                        success: function (data) {
                            if(data.error_code==0){
                                alert(data.msg);
                            }else{
                                alert('数据保存成功');
                                window.location.reload();
                            }
                        }
                        ,
                        beforeSubmit:function(){
                            
                        }
                        ,
                        // clearForm:true
                    }
                    _form.ajaxForm(options) 
                   _form.ajaxSubmit(options)
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
                $.ajaxSetup({
                    headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/dealer/modify_password_check', 
                    type: "post",
                    dataType: "json",
                    data: {
                            code:_code.val(),
                            answer:_answ.val(),
                            card_num:_card.val()
                          },
                    beforeSend: function () {
                        
                    }
                    ,
                    success: function (data) {
                        
                        var _error_code = data.error_code
                        var _error_msg = data.error_msg
                        if (_error_code == 0 || _error_code == 2) {
                            vm.errorshowhide(_code.next().next().next())
                        }else if (_error_code == 3) {
                            vm.errorshowhide(_card.next())
                        }else if (_error_code == 4) {
                            vm.errorshowhide(_answ.next())
                        }else if (_error_code == 1) {
                            window.location.href='/dealer/modify_password_input';
                        }
                        
                        
                    }
                    ,
                    error:function(){
                        
                       
                    },
            })
                
            }
        },
        
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
                    $.ajaxSetup({
                        headers: {
                          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: '/dealer/changepassword', 
                        type: "post",
                        dataType: "json",
                        data: {
                                pwd:_pwd.val(),
                              },
                        success: function (data) {
                            
                            var _error_code = data.error_code
                            var _error_msg = data.error_msg
                            if (_error_code == 1) {
                                window.location.href = "/dealer/changepasswordsuccess";//验证成功，跳转页面
                            }else{
                                alert('密码更新失败，请重新再试');
                            } 
                            
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
        selectBrandEvent:function(brand_id){
            $(this).parent().find("li").removeClass("active").end().end().addClass("active").parent().find("input[type='hidden']").val($(this).text()).parent().prev().find(".dropdown-label span").text($(this).text())
            $("input[name='brand_id']").val(brand_id)
        }
        ,
        selectDealerEvent:function(){
            $(this).parent().find("li").removeClass("active").end().end().addClass("active").parent().find("input[type='hidden']").val($(this).text()).parent().prev().find(".dropdown-label span").text($(this).text())
            $("input[name=yy_place]").val($(this).attr('yy-place'));
            $("input[name=jc_place]").val($(this).attr('jc-place'));
            $("input[name=d_id]").val($(this).attr('d-id'));
            $("#yy_place").html($(this).attr('yy-place'));
            $("#jc_place").html($(this).attr('jc-place'));
            $("#d_id").html($(this).attr('d-id'));
        }
        ,
        SubAddDealersForm:function(){
           //hfbrand  province  city hfdealers txt-dealers-shot-name
           require("module/common/hc.popup.jquery") 
           var _form       = $("form[name='add-dealers-form']")
           var _brand      = _form.find("input[name='hfbrand']")
           var _province   = _form.find("input[name='province']")
           var _city       = _form.find("input[name='city']")
           var _dealers    = _form.find("input[name='hfdealers']")
           var _shotname   = _form.find("input[name='txt-dealers-shot-name']")
           var _txtcode    = _form.find("input[name='txtcode']")
           var _account    = _form.find("input[name='bank_account']") || _form.find("input[name='account']")
           var _reg        = /^[a-zA-Z0-9]{18}$/ //选填 统一社会信用代码由18位数字或者字母
           var _regAccount = /^(\d{16}|\d{19})$/

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
           else if(!vm.isempty(_account) && !_regAccount.test(_account.val())){
              vm.errorshowhide(_account.next())
           }
           else{
               var options = {

                   success: function (data) {
                    if(data.error_code==0){
                        $("#tip-error").hcPopup()
                    }else if(data.error_code==2) {
                        $("#tip-has").hcPopup()
                    }
                    else{
                        //$("#tip-succeed").hcPopup({content:"操作成功！"})
                        //setTimeout(function(){
                            window.location.reload()
                        //},3000)
                    }
                   }
                   ,
                   beforeSubmit:function(){

                       
                   }
                   ,
                   clearForm:true
               }
               // ajaxForm 
               _form.ajaxForm(options) 
               // ajaxSubmit
               _form.ajaxSubmit(options)
                //_form.submit()
           }

        }
        ,
        noFandDelears:function(){
            require("module/common/hc.popup.jquery")
            $("#noFandDelearsWin").hcPopup({'width':'450'})
        }
        ,
        tellme:function(){            
              var options = {
                type: 'post',
                success: function(data) {
                    if (data.error_code == 1) {
                        $("#tip-succeed").hcPopup({content:"申请成功，请等待审核！"})
                    }
                    $("input[type=reset]").trigger("click");
                    $("#noFandDelearsWin").css('display','none');
                },
             }

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
              var _from = $("form[name='no-dealers-form']")
                _from.ajaxSubmit(options)
              } 
        }
        ,
        isChineseChar: function (str){   
           var reg = /[\u4E00-\u9FA5]{1,6}/g;
           return reg.test(str);
        }
        ,
        isPhoneNo:function(phone) { 
            var pattern = /^1[34578]\d{9}$/
            return pattern.test(phone)
        }
        ,
        isTelNo:function(tel) { 
            var pattern = /^[0-9]*$/
            return pattern.test(tel)
        },
        search_waitor:function(id,type){
            var _name = $("input[name='search-waitor-key']").val();
            //alert(_name)
                //return
            // if(_name==''){
            //  alert('搜索内容不能为空');
            //  return false;
            // }
            //if(type=='edit'){
                window.location.href="?search_value="+encodeURIComponent(_name);
            //}else{
                //window.location.href="/dealer/editdealer/add/"+id+"?search_value="+_name;
            
            //}
        }
        ,
        addServiceSpecialist:function(){
            require("module/common/hc.popup.jquery")
            $("#addServiceSpecialist").hcPopup({'width':'450'})
        }
        ,
        doAddServiceSpecialist:function(){
            var _this = $(this)
            if (_this.attr("disabled")) {return false}
            require("vendor/jquery.form")
            var options = {
                type: 'post',
                resetForm:true,
                beforeSubmit:function(){
                    _this.attr("disabled","disabled")
                },
                success: function(data) {
                     _this.removeAttr("disabled")
                     if(data.error_code ==0){
                         $("#tip-error").hcPopup()
                         return false;
                     }else if(data.error_code ==1){
                         //$("#tip-succeed").hcPopup()
                     }
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
            else if (!vm.isPhoneNo(_phone.val())) {
                vm.errorshowhide(_phone.next())
            }
            else if (!vm.isempty(_tel) && !vm.isTelNo(_tel.val())) {
                vm.errorshowhide(_tel.next())
            }
            else{
                _form.ajaxForm(options).ajaxSubmit(options)
            }
        }
        ,
        editServiceSpecialist:function(id){
            require("module/common/hc.popup.jquery")
            $("#editServiceSpecialist").hcPopup({'width':'450'})
            vm.specialist.name   = $(this).parent().prev().prev().prev().prev().text()
            vm.specialist.phone  = $(this).parent().prev().prev().prev().text()
            vm.specialist.tel    = $(this).parent().prev().prev().text()
            vm.specialist.remark = $(this).parent().prev().find(".showdiv").text()
            vm.specialist.id = id
            $("#editServiceSpecialist textarea").val($.trim(vm.specialist.remark))
            
        }
        ,
        doEditServiceSpecialist:function(){
            var _this = $(this)
            if (_this.attr("disabled")) {return false}
            require("vendor/jquery.form")
            var options = {
                type: 'post', 
                beforeSubmit:function(){
                    _this.attr("disabled","disabled")
                },
                success: function(data) {
                   _this.removeAttr("disabled") 
                   if(data.error_code ==0){
                       $("#tip-modify-error").hcPopup()
                       return false;
                   }else if(data.error_code ==1){
                       //$("#tip-modify-succeed").hcPopup()
                   }
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
            else if (!vm.isPhoneNo(_phone.val())) {
                vm.errorshowhide(_phone.next())
            }
            else if (!vm.isempty(_tel) && !vm.isTelNo(_tel.val())) {
                vm.errorshowhide(_tel.next())
            }
            else{
                _form.ajaxForm(options).ajaxSubmit(options)
            }

        }
        ,
        viewServiceSpecialist:function(id){
            require("module/common/hc.popup.jquery")
            $("#viewServiceSpecialist").hcPopup({'width':'450'})
            
             vm.specialist.name   = $(this).parent().prev().prev().prev().prev().text()
             vm.specialist.phone  = $(this).parent().prev().prev().prev().text()
             vm.specialist.tel    = $(this).parent().prev().prev().text()
             vm.specialist.remark = $(this).parent().prev().find(".remark-wrapper").text()
            
        }
        ,
        delServiceSpecialist:function(id){
            require("module/common/hc.popup.jquery")
            $("#delServiceSpecialist").hcPopup({'width':'450'})
            vm.specialist.id = id
            vm.specialist.model = $(this)
        }
        ,
        doDelServiceSpecialist:function(dealer_id){
            var _this = $(this)
            if (_this.attr("disabled")) {return false}
            require("vendor/jquery.form")
            _this.attr("disabled","disabled")
         
            $.ajax({
                 type: "post",
                 url: "/dealer/ajaxsubmitdealer/del-waitor/"+dealer_id,
                 data: {
                    id:vm.specialist.id,
                    _token:$("meta[name=csrf-token]").attr('content'),
                 },
                 dataType: "json",
                 success: function(data){
                     _this.removeAttr("disabled")
                     if(data.error_code ==0){
                       $("#tip-del-error").hcPopup({content:'抱歉！删除失败，请重新尝试~'})
                       return false;
                     }else if(data.error_code ==1){
                       //$("#tip-del-succeed").hcPopup()
                       //waitor-id

                     }
                    $("#delServiceSpecialist").hide()
                    var _that = vm.specialist.model
                    var _tbl  = _that.parents("table")
                    _that.parents("tr").eq(0).remove()
                    setTimeout(function(){
                        if (_tbl.find("tr").length == 2) {
                            _tbl.find("tr").eq(1).removeClass('hide')
                        }
                    },200)
                 }
            })
           
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
        add_dealer_next_action:function(step){
            require("vendor/jquery.form")
            var options = {
                type: 'post',
                success: function(data) {
                    if (data.type == 'check') {
                    var urle = window.location.href;
                    var url = urle.substr(0,urle.indexOf('?'));
                    var site_af = parseInt(url.substr(-1))+parseInt(1);
                    var site_be =url.substr(0,url.length-1);
                        window.location.href=site_be+site_af;
                    } else {
                        window.location.reload();
                    }
                    
                },
            } 
            var _form  = $("form[name='next-form']")
            if(step==3){
                _form.find('input[name="bx_type"]').val($("input[name=baoxian]:checked").val())
                _form.ajaxForm(options).ajaxSubmit(options);
            }else if(step == 2 || step == 7 || step == 6) {
             _form.ajaxForm(options).ajaxSubmit(options);
             return false;
         }
            
        } ,
        addInsuranceCompany:function(){
            var _tbl = $("#insuranceCompanyList")
            //append的内容写在#insurance-company-tmpl的js模板中(底部)
            //$.clone 没法复制avalon中的事件绑定机制
            //#insurance-company-tmpl 必须写在avalon的作用域范围外
            //不然avalon.scan方法会失败(没有任何事件绑定)
            _tbl.removeClass('hide').show().find("tbody").find("tr.no-tr").hide().end().append(
                $("#insurance-company-tmpl").html()
                )
            avalon.scan(document.getElementById('insuranceCompanyList'),avalon.vmodels["custom"])
            //清除修改，删除印记
            vm.insuranceCompany.bx_id = 0
            vm.insuranceCompany.id    = 0
            vm.insuranceCompany.model = null
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
        delInsuranceCompany:function(){
            require("module/common/hc.popup.jquery")
            $("#delInsuranceCompany").hcPopup({'width':'450'})
            vm.insuranceCompany.bx_id = $(this).attr("data-bx") 
            vm.insuranceCompany.model = $(this)
        }
        ,
        canceInsuranceCompanyAdv:function(id){
            var _this = $(this)
            var _tr   = _this.parents("tr").eq(0)
            var _tbl  = _this.parents("table")
            _tr.fadeOut(300,function(){
                _tr.remove()
                if (_tbl.find("tr.def-temp").length == 0 ) {
                    _tbl.find("tr.no-tr").remove()
                    _tbl.find("tbody").append($("#insurance-no-tmpl").html())
                }
                
            })
        }
        ,
        doDelInsuranceCompany:function(){

            if (vm.insuranceCompany.bx_id == 0) {
                $("#delInsuranceCompany").hide()
                vm.insuranceCompany.model.parents("tr").remove()
                return
            }
            $.ajax({
                 type: "POST",
                 url: "/dealer/ajaxsubmitdealer/del-baoxian/",
                 data: {
                    id:vm.insuranceCompany.bx_id,
                    _token:$("meta[name=csrf-token]").attr('content')
                 },
                 dataType: "json",
                 success: function(data){
                     if(data.error_code ==0){
                        $("#tip-error").hcPopup({content:'抱歉！删除失败，请重新尝试~'})
                        return false;
                     }else if(data.error_code ==1){
                        var _this = vm.insuranceCompany.model
                        var _tr   = _this.parents("tr").eq(0)
                        var _tbl  = _this.parents("table")
                        _tr.fadeOut(300,function(){
                            _tr.remove()
                            _tbl.find("tr.no-tr").remove()
                            if (_tbl.find("tr.def-temp").length == 0 ) {
                               _tbl.find("tbody").append($("#insurance-no-tmpl").html())
                            }
                        })
                    }
                    $("#delInsuranceCompany").hide() 
                    vm.insuranceCompany.bx_id = 0
                    vm.insuranceCompany.model = null
                 }
            })
             
        }
        ,
        virtualSave:function(_td){
            _td.find(".btn-group").hide().next().html(_td.find(".dropdown-label span").text()).removeClass('hide').show()
            _td = _td.next()
            _td.find(".checkbox-wrapper").hide().next().html(function(){
                var _arr = []
                $.each(_td.find("input[type='checkbox']"),function(){
                    if ($(this).prop("checked")) {
                        _arr.push($(this).next().text())
                    }
                })
                return _arr.join("、")
            }).removeClass('hide').show()
            _td = _td.next()
            _td.find(".save").hide().next().next().show().next().show()
        }
        ,
        saveInsuranceCompany:function(){
             
             //console.log(vm.insuranceCompany.saveid)
             //console.log($(this).attr("datadid"))
            var _this = $(this)
            if (_this.attr("disabled")) {return false}
            var _td  = _this.parents('tr').eq(0).find("td").eq(0)
            var _input = _td.find("input[type='hidden']")
            //alert(vm.insuranceCompany.id);return false;
            if ($.trim(_input.val()) == ""){ 
                vm.virtualSave(_td)
                return
            }
            $.ajax({
                 type: "post",
                 url: "/dealer/ajaxsubmitdealer/add-baoxian/",
                 data: {
                    id:_this.attr("data-id") || 0,
                    co_id:vm.insuranceCompany.saveid,
                    title:_input.val(),
                    dealer_id:$(this).attr("datadid"),
                    daili_dealer_id:$(this).attr("daili-dealer-id"),
                    _token:$("meta[name=csrf-token]").attr('content')
                 },
                 beforeSend:function(){
                    _this.attr("disabled","disabled")
                 },
                 dataType: "json",
                 success: function(data){
                     //console.log(data)
                     _this.removeAttr("disabled")
                     if(data.error_code ==0){
                           $("#tip-error").hcPopup({content:''+data.error_msg+''})
                           return false;
                       }else {
                           if (_this.hasClass('new')) {
                              _this.hide().next().remove().end().next().next().removeClass('hide none').show().next().removeClass('hide none').show()
                              _this.attr("data-bx",data.bx_id).attr("data-id",data.id).next().next().attr("data-bx",data.bx_id).attr("data-id",data.id).next().attr("data-bx",data.bx_id)
                              _this.removeClass('new')
                              _td.find("input[type='hidden']").val("")
                           }else{
                               var _la = _td.next().find(".save-label")
                               _la.attr("data-def",_la.text().length > 2 ? 1 : 0)
                           }
                           vm.virtualSave(_td)
                           //修改成功后 改变默认值
                           var _firstlable =  _td.find(".save-label")
                           _firstlable.attr("data-def",_firstlable.text())
                           //$("#tip-succeed").hcPopup()
                           //window.location.reload()
                     }
                    
                 }
            })

        }
        ,
        canceInsuranceCompany:function(){
            var _td  = $(this).parents('tr').eq(0).find("td").eq(0)
            var _label = _td.find(".btn-group").hide().next()
            _label.html(_label.attr("data-def")).removeClass('hide').show()
            _td = _td.next()
            var _checkbox      = _td.find(".checkbox-wrapper")
            var _checkboxLabel = _checkbox.hide().next()
            _checkboxLabel.html(_checkboxLabel.attr("data-def")== "0" ? "本地":"本地、异地" ).removeClass('hide').show()
            _td = _td.next()
            _td.find(".save").hide().next().next().show().next().show()
        },
        modifyInsuranceCompany:function(){
            var _this = $(this)
            var _td   = _this.parents('tr').eq(0).find("td").eq(0)
            var _area = _this.parents("td").prev()
            var _la   = _td.next().find(".save-label")

            if (_la.attr("data-def") == "1") {
                _area.find("input[type='checkbox']").prop("checked",true)
            }else{
                _area.find("input[type='checkbox']").eq(0).prop("checked",true).end().eq(1).prop("checked",false)
            }

            vm.insuranceCompany.bx_id = _this.attr("data-bx") 
            vm.insuranceCompany.id    = _this.attr("data-id") 
            vm.insuranceCompany.model = _this

            //var _td       = $(this).parents('tr').eq(0).find("td").eq(0)
            var _lable    = _td.find('.save-label')
            var _lableTxt = _lable.attr("data-def")
            var _dropdown = _td.find(".dropdown-menu")
            _lable.html('').addClass('hide').prev().css("display","inline-block").find(".dropdown-label span").text(_lableTxt)
            $.each(_dropdown.find("li"),function(){
                if ($(this).text() == _lableTxt) {
                    $(this).addClass('active').siblings().removeClass('active')
                    return false
                }
            })
            _td = _td.next()
            _td.find('.save-label').html('').addClass('hide').prev().css("display","inline-block")
            _td = _td.next()
            _td.find(".save").removeClass('hide').show().next().next().hide()
            _td.find('.save-label').html('').addClass('hide').prev().show()
            _td = _td.next()
            _td.find('.save-label').html('').addClass('hide').prev().show()
            _td = _td.next()
            _td.find(".save").show().next().hide()
        }
        ,
        save_action:function(step){
            require("vendor/jquery.form")
            var options = {
                type: 'post',
                success: function(data) {
                   if(data.error_code ==0){
                       $("#tip-error").hcPopup({content:'抱歉！修改失败，请重新尝试~'})
                       return false;
                   }else{
                       $("#tip-succeed").hcPopup({content:'恭喜，修改成功！',callback:function(){
                            //window.location.reload()
                       }})
                       
                   }
                } 
            } 
            var _form  = $("form[name='save-form']")
             _form.ajaxForm(options).ajaxSubmit(options)
            
        } 
        
    })
    
    //$(".slide").css("height",$(".content").height())

    module.exports = {
        init:function(){
             vm.init()
        }
        ,
        initInsuranceCompany:function(){
            var _tbl = $("#insuranceCompanyList")
            if (_tbl.find("tr").length <= 1) {
                _tbl.addClass('hide')
            }
        }
    }
        
})