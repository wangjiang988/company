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
            },
            delId:""
        },
        mounted:function(){
            
        }
        ,
        methods:{
            
            undo:function(id){
                $("#tipWin").hcPopup({width:420})
                this.delId = id
            },
            doUndo:function(){
                $.ajax({
                     type: "get",
                     url:"/custom/delRecord/",
                     data: {
                        id:app.delId
                     },
                     dataType: "json",
                     beforeSend: function(data){

                     },
                     success: function(data){
                        if (data.success == 1 ) {
                            $("#successWin").hcPopup({width:420})
                            app.delId = ""
                        } else {
                            $("#errorWin").hcPopup({width:420})
                        }
                        app.simpleCountDown(function(){
                            app.reload()
                        })

                     },
                     error: function(data){
                        $("#errorWin").hcPopup({width:420})
                        app.simpleCountDown(function(){
                            app.reload()
                        })
                     }
                })
                
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
