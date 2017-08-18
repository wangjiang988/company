define(function (require,exports,module) {
    var step1 = new Vue({
        el: '#vue',
        delimiters : ['${', '}'],
        unsafeDelimiters : ['{--', '--}'],//don't work --! with v-html repeat
        data: { 
            
            formValite:{
                isShotPassDisplay  :false,
                isSnDisplay        :false,
                isAccountDisplay   :false 
            }
            ,
            formInput:{
                shot_name:"",
                code:"",
                bank:"",
                account:"",
                _code:"",
                _bank:"",
                _account:""
            }
            ,
            isModifyBaseInfo:false

        },
        methods: {
           
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
                             if(data.error_code == 0 || data.error_code == 1 ){
                                $("#tip-succeed").hcPopup({content:"修改成功",callback:function(){
                                    window.location.reload()
                                }})
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
                    url: "/dealer/ajaxsubmitdealer/del-dealer",
                    type: "post",
                    dataType: "json",
                    data: { 
                        id:_dealer_id,
                        _token:$("meta[name=csrf-token]").attr('content')
                    },
                    beforeSend: function () {
                        //console.log("beforeSend")
                    }
                    ,
                    success: function (data) {
                        //console.log(data)
                        var _error_code = data.error_code;
                        var _error_msg = data.msg; 
                        var _win = _error_code == 1 ? $("#tip-succeed") : $("#tip-error") 
                        if (_error_code == 0 ) {
                           _win.hcPopup({content:_error_msg})
                        } 
                        else if (_error_code == 1) {
                           _win.hcPopup({content:_error_msg,callback:function(){
                                $("#delSeller").hide();
                                window.location.href='/dealer/editdealer/add/0'
                           }})
                        }
                        
                    }

                })
            }
            ,
            modifyBaseInfo:function(){

                this.isModifyBaseInfo   = !this.isModifyBaseInfo
                step1.formInput.code    = step1.formInput._code
                step1.formInput.bank    = step1.formInput._bank
                step1.formInput.account = step1.formInput._account
            }

        }

    })

    
    step1.$watch('formInput.code', function (val) {
        this.formValite.isSnDisplay = false
    })

    step1.$watch('formInput.account', function (val) {
        this.formValite.isAccountDisplay = false
    })
 
 
    module.exports = {
        init:function(code,bank,account){
             step1.formInput.code     = code
             step1.formInput.bank     = bank
             step1.formInput.account  = account
             step1.formInput._code    = code
             step1.formInput._bank    = bank
             step1.formInput._account = account
        }
    }



})