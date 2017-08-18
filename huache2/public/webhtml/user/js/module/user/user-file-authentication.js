define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/head")
    require("/webhtml/common/js/module/left")
    require("/webhtml/common/js/module/vue.common.mixin") 
    require("/webhtml/common/js/vendor/jquery.form")
    require("./user-area-select-component")
    require("/webhtml/common/js/vendor/hc.popup.jquery")

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
            isNameError:!1,
            isLoadingImg:!1,
            ext:[".PNG",".GIF",".JPG",".JPEG",".BMP"]

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
                       app.isLoadingImg = !0
                       event.target.setAttribute("disabled",true)
                       app.isLoadingImg = !0
                   }, 
                   success: function (data) {
                       event.target.removeAttribute("disabled");
                       app.isLoadingImg = !1
                       window.location.href = app.successUrl;
                   }
                   ,
                   error:function(){
                       app.isLoadingImg = !1
                       event.target.removeAttribute("disabled")
                   }
                }
                this.files = []
                $.each($("input[type='file']"),function(index,item){
                    if ($(item).val() != "") _this.files.push($(item).val())
                })
                //console.log(this.isChinese(this.realyName))
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
                else if(this.checkImg())
                    _form.ajaxForm(options).ajaxSubmit(options)
                
            }, 
            checkImg:function(){
                var _flag = !0
                $.each(this.files,function(inde,item){
                    var _ext = item.substr(item.indexOf(".")).toUpperCase()
                    if ($.inArray(_ext, app.ext) == -1) {
                        _flag = !1
                        app.isLoadingImg = !1
                        $("#errorWin").hcPopup({width:"420px"})
                        return false
                    }
                })
                
                return _flag
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
