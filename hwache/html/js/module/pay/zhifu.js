define(function (require,exports,module) {

    require("jq") 
    require("module/common/time.jquery") 
    var vm = avalon.define({
        $id: 'pay',
        isClick: false,
        uploadcount:0,
        init: function () {
            
        }
        ,
        selectPayMethod: function () {
            var _this = $(this)
            _this.find("span").addClass("selectpay").end().siblings('li').find("span").removeClass('selectpay')
            $("input[name='paymethod']").val(_this.index())
            $(".showerror").addClass('hide')
        }
        ,
        isSubPayForm:0
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
                if (vm.isSubPayForm == 0)  
                    $("form[name='payform']").submit()
                else
                {
                    require("module/common/hc.popup.jquery")
                    $("#subPayForm").hcPopup({'width':'400'})
                }
            }

            
        }
        ,
        doSubPayForm:function(){
            $("form[name='payform']").submit()
        }
        ,
        SendCode:function(tel){
            var _$time = 59
            var _this = $(this)
            if (vm.isClick) {
                return;
            }
            vm.isClick = true
            _this.addClass("oksure").removeClass("sure")
            $.getJSON("http://www.hwache.cn/sendcode/"+ tel, function (data) {
                var _error_code = data.error_code
                var _error_msg = data.error_msg
                //console.log(data)
                if (_error_code == 1) {
                    _phone.next().removeClass("hide").text(_error_msg)
                    return false
                }
                var _stxt = _this.attr("data-s")
                var _curtxt = _this.attr("data-send")
                 
                var _timeTMP = setInterval(function () {
                     
                    if (_$time == 0) {
                        _this.text(_stxt)
                        vm.isClick = false
                        _$time = 59
                        clearInterval(_timeTMP)
                        _this.removeClass("oksure")
                    } else {
                        _this.text(_curtxt.replace("$1", _$time));
                        _$time--
                    }
                }, 1000)
            })


        }
        ,
        surepay:function(){
            var _code = $("input[name='phonecode']")
            var _form = $("form[name='payform']")
            if ($.trim(_code.val())=="") {
                _code.focus()
                $(".inputerror").removeClass('hide').show()
                setTimeout(function(){
                    $(".inputerror").fadeOut()
                },3000)
            }
        }   
        , 
        getTime:function (day){
            re = /(\d{4})(?:-(\d{1,2})(?:-(\d{1,2}))?)?(?:\s+(\d{1,2}):(\d{1,2}):(\d{1,2}))?/.exec(day);
            return new Date(re[1],(re[2]||1)-1,re[3]||1,re[4]||0,re[5]||0,re[6]||0);
        }
        ,
        timeDiff: function (oldtime) {

            _fun = function () {

                var _now = new Date();
                var _old = vm.getTime(oldtime);
                var _diff = _now - _old

                var _totalsecond = _diff / 1000
                var _hours = Math.floor(_totalsecond / 60 / 60)
                var _minite = Math.floor(_totalsecond / 60 % 60)
                var _second = Math.floor(_totalsecond % 60)

                if (_hours > 24 || _hours < 0) {
                    $(".jishi span[class!='fuhao']").text(0)
                    clearInterval(_settime)
                    return
                } else {
                    _hours = 24 - _hours - 1
                    _minite = 60 - _minite
                    _second = 60 - _second == 60 ? 59 : 60 - _second
                    _hours = _hours.toString()
                    _minite = _minite.toString()
                    _second = _second.toString()
                    _hours = _hours.length == 2 ? _hours : "0" + _hours
                    _minite = _minite.length == 2 ? _minite : "0" + _minite
                    _second = _second.length == 2 ? _second : "0" + _second
                    $(".jishi span").eq(0).text(_hours.slice(0, 1)).end()
                                    .eq(1).text(_hours.slice(1)).end()
                                    .eq(3).text(_minite.slice(0, 1)).end()
                                    .eq(4).text(_minite.slice(1)).end()
                                    .eq(6).text(_second.slice(0, 1)).end()
                                    .eq(7).text(_second.slice(1))
                }
            }
            var _settime = setInterval(_fun, 1000)
        }
        ,
        timeOut:function(){
             
            
        }
        ,
        displayTm: function () {
            $(this).next().fadeIn(300)
        }
        ,
        hideTm: function () {
            $(this).next().hide()
        }
        ,
        closeDiaglogs:function(){
            $(this).parents(".dialogs").hide()
        }
        ,
        applyExtension:function(msg){
            //span-shixie
            $(this).hide().next().show()
            $("#dialogs-"+msg).removeClass('hide').show()
            $("#span-shixie").addClass('juhuang')
        }
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
                        $(".uploadlist").append("<span class='file-prev'>"+_val.substring(_val.lastIndexOf('\\')+1)+"</span>")
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
        
      
    });
    
    vm.init();

    module.exports = {
        initPayForm:function(flag){
           vm.isSubPayForm = flag
        }
       
    }

});