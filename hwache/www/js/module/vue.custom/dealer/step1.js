define(function (require,exports,module) {
    var step1 = new Vue({
        el: '#add-dealers-form',
        delimiters : ['${', '}'],
        unsafeDelimiters : ['{--', '--}'],//don't work --! with v-html repeat
        mixins: [mixin],
        data: { 
            sellerInfo:{
                d_yy_place:"",
                d_jc_place:"",
                d_id:"" ,
                name:""
            }
            ,
            formValite:{
                isBrandDisplay     :false,
                isProvinceDisplay  :false,
                isCityDisplay      :false,
                isProvinceDisplay2 :false,
                isCityDisplay2     :false,
                isSellerDisplay    :false,
                isShotDisplay      :false,
                isShotPassDisplay  :false,
                isSnDisplay        :false,
                isAccountDisplay   :false 
            }
            ,
            formInput:{
                shot_name:"",
                code:"",
                bank:"",
                account:""
            }
            ,
            noFindForm:{
                brand:"",
                area:"",
                textarea:"",
                isSmallBrand:false,
                isSellerEmpty:false
            }
            ,
            isModifyBaseInfo:false

        },
        methods: {
            clearSelect: function (isClearCity) {
                this.sellerInfo.d_yy_place        = ""
                this.sellerInfo.d_jc_place        = ""
                this.sellerInfo.name              = ""
                this.sellerInfo.d_id              = ""
                this.switchs[2].value             = "请选择经销商"
                this.switchs[2].selectEventSwitch = false
                this.switchs[2].id                = 0
                if (isClearCity) {
                  this.provinceTxt = ""
                  this.cityTxt     = ""
                }
            }
            ,
            getArea: function (id,index,isAssociated,v2) {
               if (isAssociated) {
                  this.switchs[index+1].value =  v2
               }
               this.switchs[index].id =  id
               this.formValite.isBrandDisplay = false
            }
            ,
            selectDealerEvent: function (index,yy,jc,id,value) {
               this.sellerInfo.d_yy_place      = yy
               this.sellerInfo.d_jc_place      = jc
               this.sellerInfo.d_id            = id
               this.switchs[index].value       = value
               this.sellerInfo.name            = value
               this.formValite.isSellerDisplay = false
               return false
            }
            ,
            isEmpty:function(index){
                return this.switchs[index].value.trim() == ""
            }
            ,
            isChineseChar: function (str){   
               var reg = /[\u4E00-\u9FA5]{1,6}/g;
               return reg.test(str);
            }
            ,
            subAddDealersForm: function () {
               require("vendor/jquery.form")
               var _flag       = true
               var _reg        = /^[a-zA-Z0-9]{18}$/ 
               //var _regAccount = /^(\d{16}|\d{19})$/
               var _regAccount = /^[0-9]*$/

               if (this.isEmpty(0)) {
                   _flag = false
                   this.formValite.isBrandDisplay = true
               }
               else if (this.provinceTxt.trim() == "") {
                   _flag = false
                   this.formValite.isProvinceDisplay = true
               }
               else if (this.cityTxt.trim() == "") {
                   _flag = false
                   this.formValite.isCityDisplay = true
               }
               else if (this.sellerInfo.name.trim() == "") {
                   _flag = false
                   this.formValite.isSellerDisplay = true
               }
               else if (!this.isChineseChar(this.formInput.shot_name)) {
                    _flag = false
                   this.formValite.isShotPassDisplay = true
               }
               else if(this.formInput.code.trim() !="" && !_reg.test(this.formInput.code)){
                   _flag = false
                   this.formValite.isSnDisplay = true
               }
               else if(this.formInput.account.trim() !="" && !_regAccount.test(this.formInput.account)){
                    _flag = false
                   this.formValite.isAccountDisplay = true
               }
               if (_flag) {
                    require("module/common/hc.popup.jquery") 
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
               return _flag
            }
            ,
            subEditDealersForm: function (id,type) {
               require("vendor/jquery.form")
               var _flag       = true
               var _reg        = /^[a-zA-Z0-9]{18}$/ 
               var _regAccount = /^[0-9]*$/
               var _this       = this
               if(this.formInput.code.trim() !="" && !_reg.test(this.formInput.code)){
                   _flag = false
                   this.formValite.isSnDisplay = true
               }
               else if(this.formInput.account.trim() !="" && !_regAccount.test(this.formInput.account)){
                    _flag = false
                   this.formValite.isAccountDisplay = true

               }
               
               //console.log(_this.formInput.code,_this.formInput.bank,_this.formInput.account,id,type)
               //return
               if (_flag) {
                    require("module/common/hc.popup.jquery") 
                     
                    $.ajax({
                         type: "post",
                         url: "/dealer/ajaxsubmitdealer/save-step/"+id, 
                         data: {
                            id:id,
                            step:0,
                            txtcode:_this.formInput.code,
                            bank:_this.formInput.bank,
                            account:_this.formInput.account,
                            type:type,
                            _token:$("meta[name=csrf-token]").attr('content')
                         },
                         dataType: "json",
                         success: function(data){
                            
                             if(data.error_code == 1 ){
                                $("#tip-succeed").hcPopup({content:"修改成功",callback:function(){
                                    window.location.reload()
                                }})
                             }
                             if(data.step) {
                                var url   = window.location.href
                                var leng  = url.length-1
                                url       = url.substr(0,leng)
                                window.location.href = url + 1
                             }

                          } 
                      
                    })
               }

               return _flag
            }
            ,
            delSeller: function (id,did) {
               require("module/common/hc.popup.jquery")
               $("#delSeller").hcPopup()
            }
            ,
            doDelSeller: function (_daili_dealer_id,_dealer_id) {
                //console.log("doDelSeller")
                $.ajax({
                    url: '/dealer/del-dealer/'+_daili_dealer_id+'/'+_dealer_id, //url为根据id获取用户信息的请求路径
                    type: "get",
                    dataType: "json",
                    data: { 
                    },
                    beforeSend: function () {
                        //console.log("beforeSend")
                    }
                    ,
                    success: function (data) {
                        //console.log(data)
                        var _error_code = data.error_code;
                        var _error_msg = data.message; 
                        var _win = _error_code == 0 ? $("#tip-succeed") : $("#tip-error") 
                        if (_error_code == 1 ) {
                           _win.hcPopup({content:_error_msg})
                        } 
                        else if (_error_code == 0) {
                           _win.hcPopup({content:_error_msg,callback:function(){
                                $("#delSeller").hide();
                                window.location.href='/dealer/editdealer/add/0'
                           }})
                        }
                        
                    }

                })
            }
            ,
            noFandDelears:function(){
                require("module/common/hc.popup.jquery")
                $("#noFandDelearsWin").hcPopup({'width':'450'})
            }
            ,
            tellme:function(){          
 
                require("module/common/hc.popup.jquery")
                var _flag = true
                var _this = this
                if (this.noFindForm.brand.trim() == "") {
                    this.noFindForm.isSmallBrand = true
                    _flag = false
                }
                else if (this.provinceTxt2 == "") {
                    this.formValite.isProvinceDisplay2 = true
                    _flag = false
                }
                else if (this.cityTxt2 == "") {
                    this.formValite.isCityDisplay2 = true
                    _flag = false
                }
                else if (this.noFindForm.textarea.trim() == "") {
                    this.noFindForm.isSellerEmpty = true
                    _flag = false
                }
                //console.log(this.noFindForm.isSmallBrand )
                if (_flag) {
                    this.noFindForm.area = this.provinceTxt2 + this.cityTxt2
                    //console.log(this.noFindForm.brand,this.noFindForm.area,this.noFindForm.textarea)  
                    var options = {
                        type: 'post',
                        resetForm:true,
                        success: function(data) {
                            if (data.error_code == 1) {
                                $("#tip-succeed").hcPopup({content:"申请成功，请等待审核！"})
                            }
                            _this.noFindForm.brand    = ""
                            _this.noFindForm.area     = ""
                            _this.provinceTxt2        = "" 
                            _this.cityTxt2            = ""
                            _this.noFindForm.textarea = ""
                            _this.switchs[3].value    = "" //清空选择后显示的内容
                            $("#noFandDelearsWin").css('display','none')

                            _this.noFindForm.isSmallBrand       = false
                            _this.formValite.isProvinceDisplay2 = false
                            _this.formValite.isCityDisplay2     = false
                            _this.noFindForm.isSellerEmpty      = false

                        }
                    }
                    var _from = $("form[name='no-dealers-form']")
                    _from.ajaxSubmit(options)

                }
                return true
            }
            ,
            modifyBaseInfo:function(){
                this.isModifyBaseInfo = !this.isModifyBaseInfo 
            }
            ,
            tellMeBack:function(){
                this.noFindForm.isSmallBrand       = false
                this.formValite.isProvinceDisplay2 = false
                this.formValite.isCityDisplay2     = false
                this.noFindForm.isSellerEmpty      = false
            }

        }

    })

    step1.$watch('formInput.shot_name', function (val) {
        this.formValite.isShotPassDisplay = false
    })

    step1.$watch('formInput.code', function (val) {
        this.formValite.isSnDisplay = false
    })

    step1.$watch('formInput.account', function (val) {
        this.formValite.isAccountDisplay = false
    })

    step1.$watch('noFindForm.brand', function (val) {
        this.noFindForm.isSmallBrand = false
    })
 
    module.exports = {
        init:function(code,bank,account){
             step1.formInput.code    = code
             step1.formInput.bank    = bank
             step1.formInput.account = account
        }
    }



})