define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/head")
    require("/webhtml/common/js/module/left")
    require("/webhtml/common/js/module/vue.common.mixin")  
    require("/webhtml/common/js/vendor/jquery.form")
    require("/webhtml/common/js/vendor/hc.popup.jquery")

    var app = new Vue({
        el: '.content',
        mixins: [mixin],
        data: {
            countDownNum:5, 
            countDownObj:{},
            form:{
                count:"",
                name:"",
                sn:"",
                countError:!1,
                nameError:!1,
                snError:!1
            }
        },
        mounted:function(){
            
        }
        ,
        methods:{
            
            send:function(event){
                if (this.form.count == "" || isNaN(this.form.count)) {
                    this.form.countError = !0
                }  
                if (this.form.name == "" ) {
                    this.form.nameError = !0
                }    
                if (this.form.sn == "" ) {
                    this.form.snError = !0
                }      
                if (!this.form.countError && !this.form.nameError && !this.form.snError) {
                    $("#tipWin").hcPopup({width:420})
                }
            },
            doSend:function(event){
                var _form  = $("form")
                var _this  = this
                var options = {
                    type: 'post' ,
                    beforeSend: function(data) {

                    },
                    success: function(data) {
                        if (data.success == 1 ) {
                            $("#successWin").hcPopup({width:420})
                            app.simpleCountDown(function(){
                                window.location.href = "S.Z.13寄送记录.html"
                            })
                        } else {
                            $("#errorWin").hcPopup({width:420})
                        }
                    },
                    error:function(){
                        $("#successWin").hcPopup({width:420})
                        app.simpleCountDown(function(){
                            window.location.href = "S.Z.13寄送记录.html"
                        })  
                    }
                }
                _form.ajaxForm(options).ajaxSubmit(options)
                
            },
            initCount:function(){
                this.form.countError = !1
            },
            checkCount:function(){
                if (this.form.count == "" || isNaN(this.form.count)) {
                    this.form.countError = !0
                } 
            },
            initName:function(){
                this.form.nameError = !1
            },
            initSn:function(){
                this.form.snError = !1
            },
            simpleCountDown:function(call){
                var _time = setInterval(function () {
                  if (app.countDownNum <=0) { 
                     clearInterval(app.countDownObj)
                     if (call) {call()}
                  }else
                    app.countDownNum--
                },1000)
                this.countDownObj = _time
            },
            reload:function(){
                window.location.reload()
            }
           
        },
        watch:{
           
        }

    })  
  
    module.exports = {
        
    }
})
