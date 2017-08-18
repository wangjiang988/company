define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/head")
    require("/webhtml/common/js/module/left")
    require("/webhtml/common/js/module/vue.common.mixin") 
    require("/webhtml/common/js/vendor/jquery.form")
    require("./user-area-select-component")

    var app = new Vue({
        el: '.user-content',
        mixins: [mixin],
        data: {
            province:"" ,
            city:"",
            bankInfo:"",
            isBankInfo:!1,
            bankNum:"",
            bankNumView:" ",
            isBankNum:!1,
            isBankError:!1,
            files:[],
            isFile:!1,
            isInput:!1,
            successUrl:"",
            isMoify:!1
        },
        mounted:function(){
            
        }
        ,
        methods:{
            send:function(event){

                var _this = this
                var _form = $("form[name='user-file-bank']")
                var options = {
                   beforeSend:function(){
                       event.target.setAttribute("disabled",true)
                   },
                   success: function (data) {
                       //event.target.removeAttribute("disabled");
                       if(data.success ==1){
                           window.location.href = data.url;
                       }
                   },
                   error:function(){
                       event.target.removeAttribute("disabled")
                   }
                }
                this.files = []
                $.each($("input[type='file']"),function(index,item){ 
                    if ($(item).val() != "") _this.files.push($(item).val())
                })
                if (this.province === "" || this.city === "") 
                    $(".area-drop-btn").addClass('error-bg')
                else if (this.bankInfo === "") 
                    this.isBankInfo = !0  
                else if (this.bankNum === "") 
                    this.isBankNum = !0
                else if(!this.luhmCheck(this.bankNum)) 
                    this.isBankError = !0  
                else if (this.files.length != $("input[type='file']").length ) 
                {
                    if (!this.isMoify) {
                        this.isFile = !0  
                    }else{
                        _form.ajaxForm(options).ajaxSubmit(options)
                    }
                }
                else 
                    _form.ajaxForm(options).ajaxSubmit(options)
                
            }, 
            checkBankInfo: function () {
                if (this.bankInfo === "") this.isBankInfo = !0
            },
            initBankInfo: function () {
                this.isBankInfo = !1
                
            },
            checkBankNum: function () {
                this.isInput = !1
                if (this.bankNum === "") this.isBankNum = !0
                else if(!this.luhmCheck(this.bankNum)) this.isBankError = !0
            },
            initBankNum: function () {
                this.isBankNum   = !1
                this.isBankError = !1
                this.isInput     = !0
            },
            valiteBankNum: function (event) {
                this.bankNum = this.replaceEmpty(this.bankNum)
            },
            getArea: function (province,city) {
                //获取省份城市 
                this.province = province
                this.city     = city
                $(".area-drop-btn").removeClass('error-bg')
            },
            upload:function(){
                this.isFile = !1
                document.getElementById('upload-file').click()
            },
            uploadHand :function(){
                this.isFile = !1
                document.getElementById('upload-file-hand').click()
            }
        }
        ,
        watch:{
            'bankNum':function(n,o){
                var _html 
                if(n.length > 4 && n.length < 8) 
                    _html = n.slice(0, 4) +" " +n.slice(4)
                else if( n.length >= 8 && n.length < 12)
                    _html = n.slice(0, 4) + " " + n.slice(4,8) + " " +n.slice(8,12)
                else if(n.length >= 12)
                    _html = n.slice(0, 4) + " " + n.slice(4,8) + " " +n.slice(8,12) + " " +n.slice(12)
                else
                    _html = n
                this.bankNumView = _html
            }
        }

    }) 
     
  
    module.exports = {
        init:function(_successUrl){
            app.successUrl = _successUrl;
        },
        initBaseInfo:function(bankInfo,bankNum){
            app.bankInfo = bankInfo
            app.bankNum = bankNum
            app.isMoify = !0
        }
    }
})
