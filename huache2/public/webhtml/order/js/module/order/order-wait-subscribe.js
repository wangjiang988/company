define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    require("/webhtml/common/js/module/vue.common.mixin") 
    require("/webhtml/common/js/vendor/hc.popup.jquery")
    require("/webhtml/common/js/vendor/jquery.form")
   

    var app = new Vue({
        el: '.content',
        mixins: [mixin],
        data: {
            continueOrder:null,
        },
        mounted:function(){

        }
        ,
        methods:{
            
            send:function () {
                if (this.continueOrder == null) {
                   $("#showhide").removeClass("hide").show()
                   setTimeout(function(){
                      $("#showhide").fadeOut(300)
                   },3000)
                }else{
                   this.continueOrder  == 'true' ? $("#sendWin").hcPopup({'width':'400'}) : $("#stopWin").hcPopup({'width':'400'})
                }
            },
            stopOrder:function () {
                $("#stopWin").hide()
                this.subForm()
            },
            doSend:function () {
                $("#sendWin").hide()
                this.subForm()
            },
            subForm:function(){
                var _form  = $("form[name='order-status-form']")
                var _this  = this
                var options = {
                    type: 'post' ,
                    beforeSend: function(data) {
                         
                    },
                    success: function(data) {
                       
                    },
                    error:function(){
                        
                    } 
                }
                _form.ajaxForm(options).ajaxSubmit(options)
            }
        },
        watch:{
            
            
        }

    })  
  
    module.exports = {
        init:function(startTime,endTime,call){
           app.init(startTime,endTime,call)
        } 
    }
})
