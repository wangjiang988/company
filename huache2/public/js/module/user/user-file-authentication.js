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
            realyName:"",
            isRealyName:!1,
            numId:"",
            numIdView:" ",
            isNumId:!1,
            isNumError:!1,
            files:[],
            isFile:!1,
            isInput:!1,
            isNameError:!1

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
                       event.target.removeAttribute("disabled");
                       window.location.href = app.successUrl;
                   }
                   ,
                   error:function(){
                       event.target.removeAttribute("disabled")
                   }
                }
                this.files = []
                $.each($("input[type='file']"),function(index,item){
                    if ($(item).val() != "") _this.files.push($(item).val())
                })
                console.log(this.isChinese(this.realyName))
                if (this.realyName === "") 
                    this.isRealyName = !0  
                if(!this.isChinese(this.realyName))
                    this.isNameError = !0
                else if (this.numId === "") 
                    this.isNumId = !0
                else if(!this.identityCodeValid(this.numId)) 
                    this.isNumError = !0  
                else if (this.files.length != $("input[type='file']").length) 
                    this.isFile = !0  
                else 
                    _form.ajaxForm(options).ajaxSubmit(options)
                
            }, 
            checkRealyName: function () {
                if (this.realyName === "") this.isRealyName = !0
            },
            initRealyName: function () {
                this.isRealyName = !1
                this.isNameError = !1
            },
            checkNumId: function () {
                this.isInput = !1
                if (this.numId === "") this.isNumId = !0
                else if(!this.identityCodeValid(this.numId)) this.isNumError = !0
            },
            initNumId: function () {
                this.isNumId   = !1
                this.isNumError = !1
                this.isInput     = !0
            },
            valiteNumId: function (event) {
                this.numId = this.replaceEmpty(this.numId)
            },
            upload:function(){
                this.isFile = !1
                document.getElementById('upload-file').click()
            },
            uploadHand :function(){
                this.isFile = !1
                document.getElementById('upload-file-hand').click()
            },
            uploadNumIdHand :function(){
                this.isFile = !1
                document.getElementById('upload-numid-hand').click()
            },
        }
        ,
        watch:{
            
        }

    }) 
     
  
    module.exports = {
        init:function(_successUrl){
            app.successUrl = _successUrl;
        }
    }
})
