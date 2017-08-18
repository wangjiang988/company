define(function (require,exports,module) {
    require("/webhtml/common/js/module/head")
    var step2 = new Vue({
        el: '#vue',
        delimiters : ['${', '}'],
        devtools : true,
        debug : true,
        unsafeDelimiters : ['{--', '--}'],//don't work --! with v-html repeat
        data: { 
            serviceSpecialist:{
                cname:   "",
                phone:   "",
                tel:     "",
                remark:  "",
                isName:  false,
                isPhone: false,
                isTel:   false,
                isSub:   true
            }
            ,
            viewService:{
                cname:   "",
                phone:   "",
                tel:     "",
                remark:  ""
            }
            ,
            editService:{
                id:      "",
                cname:   "",
                phone:   "",
                tel:     "",
                remark:  "",
                isName:  false,
                isPhone: false,
                isTel:   false,
                isSub:   true
            }
            ,
            delService:{
                id:      "",
                model:   null,
                isSub:   true
            }
            ,
            searchForm:{
               keyword:""
            }
        },
        methods: {
            
            isPhoneNo:function(phone) { 
                var pattern = /^1[34578]\d{9}$/
                return pattern.test(phone)
            }
            ,
            isTelNo:function(tel) { 
                var pattern = /^[0-9]*$/
                return pattern.test(tel)
            },
            addServiceSpecialist: function () {
               require("module/common/hc.popup.jquery")
               $("#addServiceSpecialist").hcPopup({'width':'450'})
               /*require("vendor/jquery.form")
               require("module/common/hc.popup.jquery")
               var _flag       = true
               var _reg        = /^[a-zA-Z0-9]{18}$/ 
               var _regAccount = /^(\d{16}|\d{19})$/

               if (this.isEmpty(0)) {
                   _flag = false
                   this.formValite.isBrandDisplay = true
               }
               else if(this.formInput.account.trim() !="" && !_regAccount.test(this.formInput.account)){
                    _flag = false
                   this.formValite.isAccountDisplay = true
               }
               if (_flag) {
                     
                    var _form = $("form[name='add-dealers-form']")
                    var options = {

                       success: function (data) {
                            if(data.error_code==0){
                                $("#tip-error").hcPopup()
                            }else if(data.error_code==2) {
                                $("#tip-has").hcPopup()
                            }
                            else{
                               window.location.reload()
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
               }
               return _flag*/
            }
            ,
            doAddServiceSpecialist:function(){
              
                var _this = this
                require("vendor/jquery.form")

                _this.serviceSpecialist.isName  = false
                _this.serviceSpecialist.isPhone = false
                _this.serviceSpecialist.isTel   = false

                if (_this.serviceSpecialist.isSub) {
                    
                    var options = {
                        type: 'post',
                        resetForm:true,
                        beforeSubmit:function(){
                            _this.serviceSpecialist.isSub = false
                        },
                        success: function(data) {

                             if(data.error_code ==0){
                                 $("#tip-error").hcPopup()
                                 return false;
                             }
                             _this.serviceSpecialist.cname  = ""
                             _this.serviceSpecialist.phone  = ""
                             _this.serviceSpecialist.tel    = ""
                             _this.serviceSpecialist.remark = ""
                             _this.serviceSpecialist.isSub  = true
                             $("#addServiceSpecialist").hide(function(){
                                  _this.resetSearch()
                             })
                        },
                        error:function(){
                            _this.serviceSpecialist.isSub = true
                        }

                    } 

                    var _form  = $("form[name='addServiceSpecialistForm']")
                    var _name  = _this.serviceSpecialist.cname
                    var _phone = _this.serviceSpecialist.phone
                    var _tel   = _this.serviceSpecialist.tel  

                    if (_name.trim() == "") {
                        _this.serviceSpecialist.isName  = true
                    }
                    else if (!_this.isPhoneNo(_phone)) {
                        _this.serviceSpecialist.isPhone = true
                    }
                    else if (_tel.trim() !="" && !_this.isTelNo(_tel)) {
                         _this.serviceSpecialist.isTel  = true
                    }
                    else{
                        _form.ajaxForm(options).ajaxSubmit(options)
                    }
                }
            }
            ,
            viewServiceSpecialist:function(id,name,phone,tel,remark){
                require("module/common/hc.popup.jquery")
                $("#viewServiceSpecialist").hcPopup({'width':'450'})
                 this.viewService.cname  = name
                 this.viewService.phone  = phone
                 this.viewService.tel    = tel
                 this.viewService.remark = remark
            }
            ,
            editServiceSpecialist:function(id,name,phone,tel,remark){
                require("module/common/hc.popup.jquery")
                $("#editServiceSpecialist").hcPopup({'width':'450'})
                this.editService.cname  = name
                this.editService.phone  = phone
                this.editService.tel    = tel
                this.editService.remark = remark
                this.editService.id     = id 
                
            }
            ,
            doEditServiceSpecialist:function(){
                var _this = this
                require("vendor/jquery.form")

                _this.editService.isName  = false
                _this.editService.isPhone = false
                _this.editService.isTel   = false

                //if (_this.editService.isSub) {
                    _this.editService.isSub = false

                    var options = {
                        type: 'post', 
                        beforeSubmit:function(){
                             
                        },
                        success: function(data) {
                           
                           if(data.error_code ==0){
                             $("#tip-modify-error").hcPopup()
                             return false;
                           }
                           _this.editService.cname  = ""
                           _this.editService.phone  = ""
                           _this.editService.tel    = ""
                           _this.editService.remark = ""
                           _this.editService.isSub  = true
                           $("#editServiceSpecialist").hide(function(){
                               window.location.reload()
                           })
                        } 
                    } 
                    var _form  = $("form[name='editServiceSpecialistForm']")
                    var _name  = _this.editService.cname
                    var _phone = _this.editService.phone
                    var _tel   = _this.editService.tel
                    if (_name.trim() == "") {
                        _this.editService.isName  = true
                    }
                    else if (!_this.isPhoneNo(_phone)) {
                        _this.editService.isPhone = true
                    }
                    else if (_tel.trim() !="" && !_this.isTelNo(_tel)) {
                         _this.editService.isTel  = true
                    }
                    else{
                        _form.ajaxForm(options).ajaxSubmit(options)
                    }

                //}

            }
            ,
            delServiceSpecialist:function(id){
                require("module/common/hc.popup.jquery")
                $("#delServiceSpecialist").hcPopup({'width':'450'})
                this.delService.id = id
                this.delService.model = $("#tr-id-"+id)
            }
            ,
            doDelServiceSpecialist:function(dealer_id){
                var _this = this
                require("vendor/jquery.form")

                if (_this.delService.isSub) {

                    _this.delService.isSub = false
                    $.ajax({
                         type: "post",
                         url: "/dealer/ajaxsubmitdealer/del-waitor/"+dealer_id,
                         data: {
                            id:_this.delService.id,
                            _token:$("meta[name=csrf-token]").attr('content'),
                         },
                         dataType: "json",
                         success: function(data){
                            
                            if(data.error_code ==0){
                               $("#tip-del-error").hcPopup({content:'抱歉！删除失败，请重新尝试~'})
                               return false
                            }
                            $("#delServiceSpecialist").hide()
                            _this.delService.isSub = true
                            var _that              = _this.delService.model
                            var _tbl               = _that.parents("table")
                            _that.remove()
                            setTimeout(function(){
                                if (_tbl.find("tr").length == 2) {
                                    _tbl.find("tr").eq(1).removeClass('hide')
                                }
                            },200)
                         }
                    })
                }
               
            }
            ,
            searchWaitor:function(id,type){
                window.location.href="?search_value="+encodeURIComponent(this.searchForm.keyword)
            }
            ,
            resetSearch:function(){
                var _url = window.location.href
                if (_url.indexOf("?") > 0) {
                    _url = _url.slice(0, _url.indexOf("?") )
                }
                window.location.href = _url
            }
            ,
            serviceNextAction:function(step){
                require("vendor/jquery.form")
                var options = {
                    type: 'post',
                    success: function(data) {
                        if (data.type == 'check') {
                            window.location.href = window.location.href.replace("step1","step3")
                        } else {
                            window.location.reload()
                        }
                    }
                } 
                var _form  = $("form[name='next-form']")
                if(step==3){
                    _form.find('input[name="bx_type"]').val($("input[name=baoxian]:checked").val())
                    _form.ajaxForm(options).ajaxSubmit(options)
                }else if(step == 2 || step == 7 || step == 6) {
                    _form.ajaxForm(options).ajaxSubmit(options)
                    return false
                }
              
            }
           
        }

    })

    step2.$watch('serviceSpecialist.cname', function (val) {
        this.serviceSpecialist.isName = false
    })

    step2.$watch('serviceSpecialist.phone', function (val) {
        this.serviceSpecialist.isPhone = false
    })

 
    module.exports = {
        initSearach:function(val){
             step2.searchForm.keyword = val
        }
    }



})